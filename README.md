# 📬 PHP GitHub Timeline Mailing Service

A simple PHP-based mailing system that delivers the latest public GitHub events to subscribed users **every 5 minutes**. Users verify their email before subscribing, and emails are sent using either **Sendmail** or **Gmail SMTP**. CRON jobs are used for automatic scheduling.

Made for an assignment for RTCamp.

---

## 🚀 Features

- ✅ Email subscription and verification system
- 📩 Sends GitHub timeline updates every 5 minutes
- 🔐 Unsubscribe support via unique email links
- 📨 Supports both `sendmail` and Gmail SMTP for sending emails
- ⏰ CRON job integration for scheduled dispatch
- 🗂️ File-based storage using `requirement.txt`

---

## 📁 Project Structure
├── index.php # Subscription form & verification logic

├── functions.php # Core helper functions (email, timeline fetch, code gen)

├── unsubscribe.php # Unsubscribe endpoint

├── cron.php # Script triggered by CRON to send GitHub timeline emails

├── setup_cron.php # Script to help set up CRON job scheduling

├── requirement.txt # Stores verified email addresses (acts as file-based DB)

