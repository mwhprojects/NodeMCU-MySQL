# About NodeMCU-MySQL
Uses the NodeMCU to insert two switch values into a MySQL database.

# Files
### PHP/index.php
PHP code for getting the values from the NodeMCU as well as displaying previous entries on the webpage.

### NodeMCU/NodeMCU_MySQL_LimitSwitch.ino
NodeMCU code (using the Arduino language and IDE) for "sending" the values to the MySQL database. It does this by accessing a URL that includes the switch values as parameters which the PHP GETs. (Tutorial for PHP GET method: https://www.tutorialspoint.com/php/php_get_post.htm)

# My original project
I was trying to make a system to monitor my garage door to tell me whether it's open or not. I HAD TROUBLE WITH THE NOEDMCU DEEP SLEEP so keep that in mind if you decide to use my code here.

See the progress updates on my blog: https://mwhprojects.wordpress.com/category/projects-2/nodemcu/garage-monitor/