# agent.ps1

# *** วาง Web App URL จาก Apps Script ที่นี่ ***

$ServerURL = "https://script.google.com/macros/s/AKfycbxFZcX9_DlVFBgSsKjoP1oj8A_7ehHO6gj5O2gAjZxSx-YM5zVEji6CQkK9I-pldCqr/exec"



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



$payload = @{

    hostname = $hostname; ip_address = $ip; department = "IT"; location = "Server Room"

    ram_total_gb = $ramTotal; ram_used_gb = $ramUsed; disk_total_gb = $diskTotal

    disk_free_gb = $diskFree; cpu_name = $cpu; os_name = $os; logged_user = $user

} | ConvertTo-Json



try {

    Invoke-RestMethod -Uri $ServerURL -Method Post -Body $payload -ContentType "application/json"

    Write-Host "Success: Data sent to Google Sheet." -ForegroundColor Green

} catch {

    Write-Host "Error: Check your Web App URL" -ForegroundColor Red

}