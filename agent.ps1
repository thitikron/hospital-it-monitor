# agent.ps1 (Advanced Spec Edition)
$ServerURL = "https://script.google.com/macros/s/AKfycbxFZcX9_DlVFBgSsKjoP1oj8A_7ehHO6gj5O2gAjZxSx-YM5zVEji6CQkK9I-pldCqr/exec"

# 1. ข้อมูล CPU (ดึง P-Core/E-Core และ Threads)
$cpu = Get-CimInstance Win32_Processor
# สำหรับ Gen 12 ขึ้นไป เราจะพยายามดึงรายละเอียด Core (ถ้า WMI รองรับ) 
# แต่พื้นฐานจะใช้ฟอร์แมต: 14 Cores (6P + 8E) / 20 Threads ตามที่คุณต้องการ
$cores = $cpu.NumberOfCores
$threads = $cpu.NumberOfLogicalProcessors
$pCores = $cores - ($threads - $cores) # Logic คำนวณเบื้องต้นสำหรับ Hybrid Architecture
$eCores = $cores - $pCores
$cpuCoresDisplay = "$cores Cores ($pCores`P + $eCores`E) / $threads Threads"

# 2. ข้อมูล Mainboard
$baseboard = Get-CimInstance Win32_BaseBoard
$mbDisplay = "$($baseboard.Manufacturer) $($baseboard.Product) ($($baseboard.Version))"

# 3. ข้อมูล RAM
$mem = Get-CimInstance Win32_PhysicalMemory | Select-Object -First 1
$ramTotal = [math]::Round((Get-CimInstance Win32_PhysicalMemory | Measure-Object Capacity -Sum).Sum / 1GB, 0)
$ramType = if ($mem.ConfiguredClockSpeed -ge 4800) { "DDR5" } else { "DDR4" }

# 4. ข้อมูล GPU & VRAM (เช็คเฉพาะการ์ดจอแยก)
$gpu = Get-CimInstance Win32_VideoController | Where-Object { $_.AdapterRAM -gt 1GB } | Select-Object -First 1
$gpuName = if ($gpu) { $gpu.Name } else { "On-Board Graphics" }
$gpuVRAM = if ($gpu) { "$([math]::Round($gpu.AdapterRAM / 1GB, 0)) GB" } else { "-" }

$payload = @{
    hostname   = $env:COMPUTERNAME
    ip_address = (Get-NetIPAddress -AddressFamily IPv4 | Where-Object { $_.InterfaceAlias -notlike "*Loopback*" }).IPAddress[0]
    department = "IT"
    location   = "Server Room"
    mainboard  = $mbDisplay
    cpu_name   = $cpu.Name
    cpu_cores  = $cpuCoresDisplay
    ram_total  = "$ramTotal GB"
    ram_type   = $ramType
    gpu_name   = $gpuName
    gpu_vram   = $gpuVRAM
    os_name    = (Get-CimInstance Win32_OperatingSystem).Caption
    logged_user = $env:USERNAME
} | ConvertTo-Json

try {
    Invoke-RestMethod -Uri $ServerURL -Method Post -Body $payload -ContentType "application/json"
    Write-Host "Success: Advanced Spec sent to Cloud." -ForegroundColor Green
} catch {
    Write-Host "Error: Failed to send data." -ForegroundColor Red
}
