# 🏥 Hospital IT Monitor System

ระบบตรวจสอบคอมพิวเตอร์ในโรงพยาบาลแบบ Real-time

---

## โครงสร้างไฟล์

```
hospital-monitor/
├── index.php              ← Dashboard หลัก
├── install.php            ← ติดตั้ง DB (รันครั้งแรก)
├── config.php             ← ตั้งค่า DB และ API Key
├── db.sql                 ← MySQL Schema
├── api/
│   ├── report.php         ← รับข้อมูลจาก Agent (POST)
│   ├── get_computers.php  ← ส่งข้อมูลให้ Dashboard
│   └── get_computer.php   ← รายละเอียดเครื่อง + ประวัติ
└── agent/
    ├── agent.ps1          ← PowerShell Agent (วางบน PC แต่ละเครื่อง)
    ├── location.txt       ← ระบุตำแหน่ง PC (แก้แต่ละเครื่อง)
    └── setup_task.ps1     ← ติดตั้ง Task Scheduler อัตโนมัติ
```

---

## วิธีติดตั้ง

### ฝั่ง Server (XAMPP)

1. วางโฟลเดอร์ `hospital-monitor/` ใน `C:\xampp\htdocs\`
2. เปิด XAMPP → Start Apache + MySQL
3. แก้ไข `config.php`:
   - `HOSPITAL_NAME` → ชื่อรพ.
   - `API_KEY` → เปลี่ยนให้ยากๆ
4. เปิดเบราว์เซอร์: `http://localhost/hospital-monitor/install.php`
5. กด Import DB → จะสร้างตารางอัตโนมัติ
6. ลบไฟล์ `install.php` ออก

### ฝั่ง Client PC แต่ละเครื่อง

1. สร้างโฟลเดอร์ `C:\IT\`
2. คัดลอก `agent.ps1` และ `location.txt` ไปไว้ที่ `C:\IT\`
3. แก้ไข `agent.ps1`:
   - `$ServerURL` → IP ของเซิร์ฟเวอร์ XAMPP
   - `$ApiKey` → ต้องตรงกับ config.php
4. แก้ไข `C:\IT\location.txt`:
   ```
   location=ห้อง OPD ชั้น 1 เคาน์เตอร์ A
   department=ผู้ป่วยนอก (OPD)
   ```
5. รัน `setup_task.ps1` ในฐานะ **Administrator** → จะติดตั้ง Task Scheduler อัตโนมัติ

---

## ข้อมูลที่เก็บ

| ประเภท | รายละเอียด |
|--------|-----------|
| ตำแหน่ง | Hostname, IP, MAC, Location, แผนก, Domain |
| Hardware | ยี่ห้อ, รุ่น, Serial Number, BIOS |
| CPU | ชื่อ, Core, Thread, ความเร็ว |
| RAM | Total, ใช้งาน, จำนวน Slot |
| Storage | Total, Free, รุ่น HDD/SSD |
| OS | ชื่อ, Version, Build, Architecture |
| GPU | ชื่อการ์ดจอ |
| ผู้ใช้ | Username ที่ Login อยู่ |

---

## Features

- 🟢 **Online/Offline** — ตรวจสอบแบบ Real-time (refresh ทุก 30 วินาที)
- 🔍 **Search** — ค้นหาด้วย hostname, IP, แผนก, รุ่น, S/N
- 📊 **Progress Bar** — แสดง RAM และ Disk usage
- 📋 **Detail Modal** — ดูข้อมูลครบทุก spec
- 📝 **Notes** — IT admin เพิ่มหมายเหตุได้
- 📜 **Log History** — เก็บประวัติ 24 ชั่วโมง
- ⬇ **Export CSV** — ส่งออกข้อมูลทั้งหมด
- 🔃 **Auto Refresh** — อัปเดตอัตโนมัติทุก 30 วินาที

---

## ความปลอดภัย

- ✅ ใช้ API Key ป้องกัน Endpoint
- ✅ PDO Prepared Statements ป้องกัน SQL Injection
- ✅ ควรใช้งานบน Internal Network เท่านั้น
- ✅ ลบ install.php หลังติดตั้ง
- ⚠ ถ้าต้องการ HTTPS ให้ตั้ง SSL บน Apache
