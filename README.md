# 3-Tier Tooling Application Deployment

This repository documents a hands-on deployment of a 3-tier web
application where the application components are separated across
dedicated servers for **Database (MariaDB)**, **Storage (NFS)**, and
**Web (Apache + PHP)** tiers. The goal was to provide a repeatable,
secure, and maintainable setup where multiple web servers share the same
application files via NFS and connect to a single secured database
instance.

------------------------------------------------------------------------

### Architecture Diagram

<img width="813" height="472" alt="Screen Shot 2025-12-02 at 11 25 49" src="https://github.com/user-attachments/assets/aa925d13-0cdc-4f4e-873b-3b9df5614c20" />


Traffic flow: - **Client traffic**: browser → Web Servers\
- **DB traffic**: Web Servers ↔ DB Server (MariaDB)\
- **NFS traffic**: Web Servers ↔ NFS Server (shared files & logs)

------------------------------------------------------------------------

### Step 1 --- Database server (MariaDB)

1.  **Install MariaDB**

        sudo yum install -y mariadb-server
        sudo systemctl enable --now mariadb
        sudo mysql_secure_installation
<img width="1173" height="564" alt="Screen Shot 2025-11-21 at 11 16 21" src="https://github.com/user-attachments/assets/265579f6-cf7a-4527-95c2-4fb1efc178eb" />

2.  **Create database and application user**

        sudo mysql -u root -p
        CREATE DATABASE tooling;
        CREATE USER 'webaccess'@'13.48.24.%' IDENTIFIED BY '34257607Mose';
        GRANT ALL PRIVILEGES ON tooling.* TO 'webaccess'@'13.48.24.%';
        FLUSH PRIVILEGES;
        EXIT;

3.  **Restrict network access to port 3306**

        sudo firewall-cmd --permanent --new-zone=webtier
        sudo firewall-cmd --permanent --zone=webtier --add-source=13.48.24.0/24
        sudo firewall-cmd --permanent --zone=webtier --add-port=3306/tcp
        sudo firewall-cmd --reload

4.  **Verify remote access**

        mysql -h 172.31.40.139 -u webaccess -p tooling
<img width="1188" height="151" alt="Screen Shot 2025-11-21 at 11 20 48" src="https://github.com/user-attachments/assets/a343625b-8651-4baf-9bdf-427058f67d69" />

------------------------------------------------------------------------

### Step 2 --- NFS Storage Server

1.  **Clone repository**

        git clone https://github.com/yourusername/tooling.git /tmp/tooling

2.  **Install and enable NFS**

        sudo yum install -y nfs-utils
        sudo systemctl enable --now nfs-server rpcbind

3.  **Create export directories**

        sudo mkdir -p /mnt/apps /mnt/logs
        sudo cp -r /tmp/tooling/html/* /mnt/apps/

4.  **Set permissions**

        sudo chown -R nobody:nobody /mnt/apps /mnt/logs
        sudo chmod -R 755 /mnt/apps

5.  **Configure exports**

        /mnt/apps   13.48.24.0/24(rw,sync,no_subtree_check,no_root_squash)
        /mnt/logs   13.48.24.0/24(rw,sync,no_subtree_check,no_root_squash)

6.  **Open firewall**

        sudo firewall-cmd --permanent --add-service=nfs
        sudo firewall-cmd --permanent --add-service=rpc-bind
        sudo firewall-cmd --permanent --add-service=mountd
        sudo firewall-cmd --reload
    <img width="1227" height="478" alt="Screen Shot 2025-11-12 at 20 58 19" src="https://github.com/user-attachments/assets/5adbdbfa-b83c-4179-bb9c-dc7f5a195fb9" />
<img width="1223" height="415" alt="Screen Shot 2025-11-12 at 20 57 48" src="https://github.com/user-attachments/assets/2efd21a0-d3fb-4834-b804-b9a164955900" />

-------------------------------------------

### Step 3 --- Web Servers (repeat on each)

1.  **Install packages**

        sudo yum install -y httpd php php-mysqlnd nfs-utils
        sudo systemctl enable --now httpd

2.  **Mount NFS shares**

        sudo mount 16.170.243.185:/mnt/apps /var/www/html
        sudo mount 16.170.243.185:/mnt/logs /var/log/httpd

3.  **Persist mounts**

        16.170.243.185:/mnt/apps  /var/www/html  nfs  defaults,_netdev  0 0
        16.170.243.185:/mnt/logs  /var/log/httpd  nfs  defaults,_netdev  0 0

4.  **SELinux**

        sudo setsebool -P httpd_use_nfs on

5.  **Firewall**

        sudo firewall-cmd --permanent --add-service=http
        sudo firewall-cmd --permanent --add-service=https
        sudo firewall-cmd --reload
<img width="1258" height="587" alt="Screen Shot 2025-11-12 at 22 10 18" src="https://github.com/user-attachments/assets/59f6d96d-82d2-46cf-b073-46b127d58ec2" />

------------------------------------------------------------------------

### Step 4 --- Application & Database Setup

1.  **Update DB config**

        $servername = "172.31.40.139";
        $username   = "webaccess";
        $password   = "34257607Mose";
        $dbname     = "tooling";

2.  **Import SQL**

        mysql -h 172.31.40.139 -u webaccess -p tooling < /mnt/apps/tooling-db.sql

3.  **Create admin user**

        INSERT INTO users (id, username, password, email, role, status)
        VALUES (2, 'myuser', '5f4dcc3b5aa765d61d8327deb882cf99', 'user@mail.com', 'admin', '1');

------------------------------------------------------------------------

### Step 5 --- Testing

1.  Check mounts:

        mount | grep nfs

2.  Access web app:

        http://<WEB_SERVER_PUBLIC_IP>/index.php

3.  Verify DB:

        mysql -h 172.31.40.139 -u webaccess -p -e "SHOW TABLES;" tooling
    <img width="1145" height="314" alt="Screen Shot 2025-11-21 at 11 19 37" src="https://github.com/user-attachments/assets/3ffb5794-890f-49a2-9b21-f56b5aeb7c91" />

