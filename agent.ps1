# agent.ps1
# *** วาง Web App URL ที่ได้จาก Google Apps Script Deployment ที่นี่ ***
$ServerURL = "ใส่_Web_App_URL_ของคุณที่นี่"

Write-Host "=== Hospital IT Monitor Cloud Agent v2.0 ===" -ForegroundColor Cyan

# เก็บข้อมูลสเปคเครื่อง
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

# อ่านตำแหน่งจากไฟล์ (ถ้ามี)
$location = "ไม่ระบุ"
$dept = "ไม่ระบุ"
if (Test-Path "C:\IT\location.txt") {
    $locFile = Get-Content "C:\IT\location.txt" | ConvertFrom-StringData
    $location = $locFile.location
    $dept = $locFile.department
}

# เตรียมข้อมูล JSON
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

# ส่งข้อมูลเข้า Google Sheets
try {
    Invoke-RestMethod -Uri $ServerURL -Method Post -Body $payload -ContentType "application/json"
    Write-Host "Collected: $hostname | $ip | $cpu" -ForegroundColor Green
    Write-Host "Status: Success sent to Cloud Sheet" -ForegroundColor Green
} catch {
    Write-Host "Error: Could not connect to Cloud" -ForegroundColor Red
}
