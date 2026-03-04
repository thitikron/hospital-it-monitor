# =====================================================
# agent.ps1 v2 — Hospital IT Monitor Agent
# ดึง RAM Type (DDR3/4/5) และ Monitor + SN
# =====================================================

# ======= แก้ไขตรงนี้ =========
$ServerURL    = "http://192.168.1.100/hospital-monitor/api/report.php"
$ApiKey       = "HOSP-MONITOR-2024-CHANGE-ME"
$AgentVersion = "2.0"
# ==============================

$LocationFile = "D:\Programs\location.txt"

function Get-LocationFromFile {
    if (Test-Path $LocationFile) {
        $lines = (Get-Content $LocationFile -Raw -Encoding UTF8) -split "`n" | ForEach-Object { $_.Trim() }
        $loc  = ($lines | Where-Object { $_ -match "^location\s*=" }) -replace "^location\s*=\s*",""  | Select-Object -First 1
        $dept = ($lines | Where-Object { $_ -match "^department\s*=" }) -replace "^department\s*=\s*","" | Select-Object -First 1
        return @{ Location = $loc; Department = $dept }
    }
    return @{ Location = $null; Department = $null }
}

function Get-RamType {
    # SMBIOSMemoryType: 20=DDR2, 24=DDR3, 26=DDR4, 34=DDR5
    $typeMap = @{20='DDR2'; 24='DDR3'; 26='DDR4'; 34='DDR5'; 0='Unknown'}
    try {
        $mem = Get-CimInstance Win32_PhysicalMemory | Select-Object -First 1
        $t = [int]($mem.SMBIOSMemoryType)
        return if ($typeMap.ContainsKey($t)) { $typeMap[$t] } else { "DDR($t)" }
    } catch { return $null }
}

function Get-MonitorInfo {
    $result = @()
    try {
        $monitors = Get-CimInstance -Namespace root\wmi -ClassName WmiMonitorID -ErrorAction Stop
        foreach ($m in $monitors) {
            $name = ($m.UserFriendlyName | Where-Object {$_ -ne 0} | ForEach-Object {[char]$_}) -join ''
            $sn   = ($m.SerialNumberID   | Where-Object {$_ -ne 0} | ForEach-Object {[char]$_}) -join ''
            if ($name) {
                $result += @{ name = $name.Trim(); sn = $sn.Trim() }
            }
        }
    } catch {
        # บาง PC ไม่มี WMI Monitor → ข้ามได้
    }
    return $result
}

function Get-IPInfo {
    $adapter = Get-NetIPAddress -AddressFamily IPv4 |
               Where-Object { $_.IPAddress -notlike "127.*" -and $_.IPAddress -notlike "169.*" } |
               Sort-Object PrefixLength -Descending | Select-Object -First 1
    $mac = (Get-NetAdapter | Where-Object { $_.ifIndex -eq $adapter.InterfaceIndex } | Select-Object -First 1).MacAddress
    return @{ IPAddress = $adapter.IPAddress; MacAddress = $mac }
}

function Get-AllInfo {
    try {
        $cs     = Get-CimInstance Win32_ComputerSystem
        $bios   = Get-CimInstance Win32_BIOS
        $cpu    = Get-CimInstance Win32_Processor | Select-Object -First 1
        $os     = Get-CimInstance Win32_OperatingSystem
        $ramMem = Get-CimInstance Win32_PhysicalMemory
        $disk   = Get-CimInstance Win32_LogicalDisk -Filter "DeviceID='C:'"
        $diskPh = Get-CimInstance Win32_DiskDrive | Select-Object -First 1
        $gpu    = Get-CimInstance Win32_VideoController | Select-Object -First 1

        $totalRamGB = [math]::Round($cs.TotalPhysicalMemory / 1GB, 2)
        $freeRamGB  = [math]::Round($os.FreePhysicalMemory * 1KB / 1GB, 2)
        $usedRamGB  = [math]::Round($totalRamGB - $freeRamGB, 2)

        $net     = Get-IPInfo
        $loc     = Get-LocationFromFile
        $mons    = Get-MonitorInfo
        $ramType = Get-RamType

        return @{
            hostname         = $env:COMPUTERNAME
            location         = $loc.Location
            department       = $loc.Department
            ip_address       = $net.IPAddress
            mac_address      = $net.MacAddress
            manufacturer     = $cs.Manufacturer
            model            = $cs.Model
            serial_number    = $bios.SerialNumber
            bios_version     = $bios.SMBIOSBIOSVersion
            cpu_name         = $cpu.Name.Trim()
            cpu_id           = $cpu.ProcessorId.Trim()
            cpu_cores        = $cpu.NumberOfCores
            cpu_threads      = $cpu.NumberOfLogicalProcessors
            cpu_speed_mhz    = $cpu.MaxClockSpeed
            ram_total_gb     = $totalRamGB
            ram_used_gb      = $usedRamGB
            ram_slots        = ($ramMem | Measure-Object).Count
            ram_type         = $ramType
            disk_total_gb    = [math]::Round($disk.Size / 1GB, 2)
            disk_free_gb     = [math]::Round($disk.FreeSpace / 1GB, 2)
            disk_model       = $diskPh.Model
            monitors         = $mons          # array [{name, sn}, ...]
            os_name          = $os.Caption
            os_version       = $os.Version
            os_build         = $os.BuildNumber
            os_architecture  = $os.OSArchitecture
            last_boot        = $os.LastBootUpTime.ToString("yyyy-MM-dd HH:mm:ss")
            gpu_name         = $gpu.Caption
            domain           = $cs.Domain
            logged_user      = $env:USERNAME
            agent_version    = $AgentVersion
        }
    } catch {
        Write-Host "ERROR: $_" -ForegroundColor Red
        return $null
    }
}

function Send-Data($Data) {
    $json    = $Data | ConvertTo-Json -Depth 5 -Compress
    $headers = @{ "Content-Type"="application/json"; "X-API-Key"=$ApiKey }
    try {
        $res = Invoke-RestMethod -Uri $ServerURL -Method POST -Body $json -Headers $headers -TimeoutSec 15
        if ($res.success) {
            Write-Host "[OK] $($Data.hostname) → $($Data.ip_address) | RAM: $($Data.ram_type) | Monitors: $($Data.monitors.Count)" -ForegroundColor Green
        } else {
            Write-Host "[FAIL] $($res.error)" -ForegroundColor Red
        }
    } catch {
        Write-Host "[ERROR] $_" -ForegroundColor Red
    }
}

# MAIN
Write-Host "=== Hospital IT Monitor Agent v$AgentVersion ===" -ForegroundColor Cyan
$info = Get-AllInfo
if ($info) { Send-Data -Data $info }
Write-Host "Done." -ForegroundColor Cyan
