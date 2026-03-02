# =====================================================
# agent.ps1 — Hospital IT Monitor Agent
# วิ่งบน Windows PC แต่ละเครื่องใน รพ.
#
# วิธีติดตั้ง:
#   1. แก้ไขค่า $ServerURL และ $ApiKey ด้านล่าง
#   2. ตั้ง Windows Task Scheduler ให้รันทุก 5 นาที
#      หรือตอน Logon ผู้ใช้
#
# คำสั่ง Task Scheduler:
#   powershell.exe -WindowStyle Hidden -ExecutionPolicy Bypass -File "C:\IT\agent.ps1"
# =====================================================

# ======= แก้ไขตรงนี้ =========
$ServerURL = "http://127.0.0.1/hospital-monitor/api/report.php" # ← IP เซิร์ฟเวอร์ XAMPP
$ApiKey = "HOSP-MONITOR-2024-CHANGE-ME"                            # ← ต้องตรงกับ config.php
$AgentVersion = "1.0"
# ==============================

# Location/Department (แก้ได้ตาม PC แต่ละเครื่อง หรือดึงจากไฟล์)
# ถ้าไม่ตั้ง จะพยายามหาจาก AD/Registry
$LocationFile = "C:\IT\location.txt"  # ถ้ามีไฟล์นี้ จะอ่าน location จากนี้

function Get-LocationFromFile {
    if (Test-Path $LocationFile) {
        $content = Get-Content $LocationFile -Raw
        $lines   = $content -split "`n" | ForEach-Object { $_.Trim() }
        $loc  = ($lines | Where-Object { $_ -match "^location\s*=" }) -replace "^location\s*=\s*","" | Select-Object -First 1
        $dept = ($lines | Where-Object { $_ -match "^department\s*=" }) -replace "^department\s*=\s*","" | Select-Object -First 1
        return @{ Location = $loc; Department = $dept }
    }
    return @{ Location = $null; Department = $null }
}

function Get-IPInfo {
    $adapters = Get-NetIPAddress -AddressFamily IPv4 |
                Where-Object { $_.IPAddress -notlike "127.*" -and $_.IPAddress -notlike "169.*" }
    $primary = $adapters | Sort-Object -Property PrefixLength -Descending | Select-Object -First 1
    $mac = (Get-NetAdapter | Where-Object { $_.Name -eq (Get-NetIPInterface -InterfaceIndex $primary.InterfaceIndex).InterfaceAlias } |
            Select-Object -First 1).MacAddress
    return @{
        IPAddress  = $primary.IPAddress
        MacAddress = $mac
    }
}

function Get-ComputerInfo {
    try {
        # ---- System / Hardware ----
        $cs    = Get-CimInstance -ClassName Win32_ComputerSystem
        $bios  = Get-CimInstance -ClassName Win32_BIOS
        $board = Get-CimInstance -ClassName Win32_BaseBoard

        # ---- CPU ----
        $cpu = Get-CimInstance -ClassName Win32_Processor | Select-Object -First 1

        # ---- RAM ----
        $os      = Get-CimInstance -ClassName Win32_OperatingSystem
        $ramBank = Get-CimInstance -ClassName Win32_PhysicalMemory
        $ramSlots = ($ramBank | Measure-Object).Count

        $totalRamGB = [math]::Round($cs.TotalPhysicalMemory / 1GB, 2)
        $freeRamGB  = [math]::Round($os.FreePhysicalMemory  / 1MB, 2)  # KB→GB
        $freeRamGB  = [math]::Round($os.FreePhysicalMemory  * 1KB / 1GB, 2)
        $usedRamGB  = [math]::Round($totalRamGB - $freeRamGB, 2)

        # ---- Disk ----
        $disk = Get-CimInstance -ClassName Win32_LogicalDisk -Filter "DeviceID='C:'"
        $diskTotalGB = [math]::Round($disk.Size / 1GB, 2)
        $diskFreeGB  = [math]::Round($disk.FreeSpace / 1GB, 2)

        # Disk physical model
        $diskPhysical = Get-CimInstance -ClassName Win32_DiskDrive | Select-Object -First 1

        # ---- GPU ----
        $gpu = Get-CimInstance -ClassName Win32_VideoController | Select-Object -First 1

        # ---- OS ----
        $osInfo = Get-CimInstance -ClassName Win32_OperatingSystem
        $lastBoot = $osInfo.LastBootUpTime.ToString("yyyy-MM-dd HH:mm:ss")

        # ---- Logged User ----
        $loggedUser = $env:USERNAME

        # ---- Network ----
        $netInfo = Get-IPInfo

        # ---- Location ----
        $locInfo = Get-LocationFromFile
        $location   = if ($locInfo.Location)   { $locInfo.Location }   else { $null }
        $department = if ($locInfo.Department) { $locInfo.Department } else { $null }

        return @{
            hostname        = $env:COMPUTERNAME
            location        = $location
            department      = $department
            ip_address      = $netInfo.IPAddress
            mac_address     = $netInfo.MacAddress
            manufacturer    = $cs.Manufacturer
            model           = $cs.Model
            serial_number   = $bios.SerialNumber
            bios_version    = $bios.SMBIOSBIOSVersion
            cpu_name        = $cpu.Name.Trim()
            cpu_cores       = $cpu.NumberOfCores
            cpu_threads     = $cpu.NumberOfLogicalProcessors
            cpu_speed_mhz   = $cpu.MaxClockSpeed
            ram_total_gb    = $totalRamGB
            ram_used_gb     = $usedRamGB
            ram_slots       = $ramSlots
            disk_total_gb   = $diskTotalGB
            disk_free_gb    = $diskFreeGB
            disk_model      = $diskPhysical.Model
            os_name         = $osInfo.Caption
            os_version      = $osInfo.Version
            os_build        = $osInfo.BuildNumber
            os_architecture = $osInfo.OSArchitecture
            last_boot       = $lastBoot
            gpu_name        = $gpu.Caption
            domain          = $cs.Domain
            logged_user     = $loggedUser
            agent_version   = $AgentVersion
        }
    } catch {
        Write-Host "ERROR collecting info: $_" -ForegroundColor Red
        return $null
    }
}

function Send-ToServer {
    param ($Data)

    $json    = $Data | ConvertTo-Json -Compress
    $headers = @{
        "Content-Type" = "application/json"
        "X-API-Key"    = $ApiKey
    }

    try {
        $response = Invoke-RestMethod -Uri $ServerURL -Method POST -Body $json -Headers $headers -TimeoutSec 15
        if ($response.success) {
            Write-Host "[OK] Reported: $($Data.hostname) → $($Data.ip_address) ($($response.action))" -ForegroundColor Green
        } else {
            Write-Host "[FAIL] Server error: $($response.error)" -ForegroundColor Red
        }
        return $response
    } catch {
        Write-Host "[ERROR] Cannot reach server: $_" -ForegroundColor Red
        return $null
    }
}

# =====================================================
# MAIN
# =====================================================
Write-Host "=== Hospital IT Monitor Agent v$AgentVersion ===" -ForegroundColor Cyan
Write-Host "Server: $ServerURL"

$info = Get-ComputerInfo

if ($null -eq $info) {
    Write-Host "Failed to collect system info." -ForegroundColor Red
    exit 1
}

Write-Host "Collected: $($info.hostname) | $($info.ip_address) | $($info.cpu_name)"
Send-ToServer -Data $info

Write-Host "Done." -ForegroundColor Cyan
