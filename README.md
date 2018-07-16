# This is demo project which implements sending notifications to clients of fitness club

For run project on local cpu should be done follow requirements and steps.
Better use Linux or Mac, Windows not tested.

# Requirements

- docker-compose
- php 7.1 with ampq extension
- free ports: 8000, 3306, 8025, 1025, 15672, 5672

# Steps for running

- Open new console window go to docker dir and run docker-compose up
- Open new console window and run project_dir/bin/console server:run
- Open new console window and run tail -f project_dir/var/log/sms.log
- Open new console window and run project_dir/bin/init and project_dir/bin/console enqueue:consume --setup-broker
- Open web browser and open http://localhost:8000
- Open web browser and open http://localhost:8025
- Admin has login, password: admin:admin

# Step for demo

- Create client record, confirmation send to local mail server which accessable in web browser(http://localhost:8025)
- Copy confirmation link from email and open in another web browser type
- Enter new password and then possible login by client to his dashboard
- Create fitness coach in admin area
- Create group fitness class in admin area
- Subscribe to group fitness class in client area
- Open group fitness class Notification tab
- Fill fields and click send button
- If client has email subscription type then you can see email in http://localhost:8025
- If client has sms subscription type then you can see sms log in console window with tail -f project_dir/var/log/sms.log
- Sms log shows http code and message(This is fake sms gateway created for demo, controller returns 200 or 503 http code randomly)

# Options

- If you want change sms delay possible check project_dir/.env file
