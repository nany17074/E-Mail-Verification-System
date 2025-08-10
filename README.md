# E-Mail-Verification-System
A sleek PHP-powered email verification system that delights verified users with a daily dose of random XKCD comics. Seamlessly automated using CRON and shell scripts—secure, smart, and seriously fun. Backend brilliance meets geeky humor!

---

**Tech Stack:** PHP, Shell Script, CRON, MySQL

This project is a lightweight and fully automated **PHP-based email verification and comic delivery system**. It allows users to register with their email address, verify it via a unique code, and then receive **a random XKCD comic** in their inbox every 24 hours. 🧠✨

💡 Features

* ✅ **Email verification system** built in PHP
* 🧾 **Secure registration** flow with unique verification codes
* 🔁 **Automated daily emails** using CRON jobs and shell scripting
* 🎨 **Random XKCD comic** fetched and embedded from the [official XKCD API](https://xkcd.com/json.html)
* 📂 Clean modular structure using PHP and MySQL

---
 🛠 How It Works

1. **User Registration**:
   Users enter their email via a simple web form. Upon submission, the system sends them a unique verification code.

2. **Email Verification**:
   Once the user verifies their email, their address is securely stored in the database as "active."

3. **CRON Automation**:
   A **shell script** runs daily via a CRON job. It:

   * Picks a random XKCD comic
   * Uses PHP to format and send the comic via email
   * Sends the comic to **all verified users**
---
 🚀 Why This Project?

* To combine **PHP backend logic** with **UNIX-based automation**
* To explore real-world usage of **CRON and shell scripting** for periodic tasks
* To demonstrate a practical, fun application that merges **web dev + automation**

---

📧 Sample Email Preview

> **Subject:** Your Random XKCD Comic of the Day!
> *(Comic Image)*
> *"Here's your daily dose of geeky humor straight from XKCD. Enjoy!"*

---


⏱ CRON Setup Example

```bash
# Run the comic mailer every day at 10 AM
0 10 * * * /bin/bash /path/to/scripts/daily_cron.sh
```

---
📌 Future Enhancements

* Comic delivery scheduling preferences (daily/weekly)
* Unsubscribe option via email link
* Web dashboard to manage subscriptions
* Comic caching and history tracking

---
👨‍💻 Built With Love Using:

* **PHP** for backend and mailing logic
* **Shell Script** to automate the cron pipeline
* **MySQL** for user data storage
* **CRON** to power the daily delivery

---
⭐ If you like this project, feel free to star 🌟 and fork 🍴

Pull requests and contributions are welcome!

---

