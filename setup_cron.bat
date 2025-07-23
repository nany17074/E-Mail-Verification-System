schtasks /create /tn "XKCDComicSender" /tr "php C:\xampp\htdocs\xkcd-subscription\cron.php" /sc daily /st 00:00
