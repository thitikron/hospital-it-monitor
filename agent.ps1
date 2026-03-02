# =========================================================
# Hospital IT Monitor Agent v2.1 (Cloud Version)
# =========================================================

# URL ที่ได้รับจาก Google Apps Script
$ServerURL = "https://script.google.com/macros/s/AKfycbyWg8Xju3VVWhdQYhjbGiZ1P_VVr5OyKtzyoQENvik4hDOC5nJ-0RIhvnExNCdp8nxU/exec"

Write-Host "=== Hospital IT Monitor Agent v2.1 (Cloud) ===" -ForegroundColor Cyan

# 1. รวบรวมข้อมูล Hardware และ System
$hostname = $env:COMPUTERNAME
$ip = (Get-NetIPAddress -AddressFamily IPv4 | Where-Object { $_.InterfaceAlias -notlike "*Loopback*" }).IPAddress[0]
$cpu = (Get-WmiObject Win32_Processor).Name
$ramTotal = [math]::Round((Get-WmiObject Win32_PhysicalMemory | Measure-Object Capacity -Sum).Sum / 1GB, 2)
$ramFree = [math]::Round((Get-WmiObject Win32_OperatingSystem).FreePhysicalMemory / 1MB, 2)
$ramUsed = [math]::Round($ramTotal - ($ramFree / 1024), 2)
$disk = Get-WmiObject Win32_LogicalDisk -Filter "DeviceID='C:'"
$diskTotal = [math]::Round($disk.Size / 1GB, 2)
$diskFree = [math]::Round($disk.FreeSpace / 1GB, 2)
$os = (Get-WmiObject Win32_OperatingSystem).Caption
$user = $env:USERNAME

# 2. อ่านข้อมูลตำแหน่งจากไฟล์ C:\IT\location.txt (ถ้ามี)
$location = "ไม่ระบุ"
$dept = "ไม่ระบุ"
if (Test-Path "C:\IT\location.txt") {
    $locFile = Get-Content "C:\IT\location.txt" | ConvertFrom-StringData
    $location = $locFile.location
    $dept = $locFile.department
}

# 3. เตรียมโครงสร้างข้อมูล JSON สำหรับส่งไปยัง Google Sheets
$payload = @{
    hostname      = $hostname
    ip_address    = $ip
    department    = $dept
    location      = $location
    ram_total_gb  = $ramTotal
    ram_used_gb   = $ramUsed
    disk_total_gb = $diskTotal
    disk_free_gb  = $diskFree
    cpu_name      = $cpu
    os_name       = $os
    logged_user   = $user
} | ConvertTo-Json

# 4. ส่งข้อมูลไปยัง Cloud API
try {
    Write-Host "Sending data to Cloud..." -NoNewline
    $response = Invoke-RestMethod -Uri $ServerURL -Method Post -Body $payload -ContentType "application/json"
    
    if ($response.status -eq "success") {
        Write-Host " [OK]" -ForegroundColor Green
        Write-Host "Collected: $hostname | $ip | $cpu" -ForegroundColor Gray
    } else {
        Write-Host " [FAILED]" -ForegroundColor Red
        Write-Host "Server Response: $($response.message)" -ForegroundColor Yellow
    }
} catch {
    Write-Host " [ERROR]" -ForegroundColor Red
    Write-Host "Message: $($_.Exception.Message)" -ForegroundColor Yellow
}
