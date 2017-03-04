/*
 * Description: Sends sensor value to MySQL database (by inserting values into a URL for PHP to GET) then goes into deep sleep for a minute.
 * Author: Matthew W. - www.mwhprojects.com - www.github.com/mwhprojects/NodeMCU-MySQL
 */
 
#include <ESP8266WiFi.h>
const char* ssid  = "YOUR_WIFI_SSID";
const char* password = "YOUR_WIFI_PASSWORD";
const char* host = "YOUR_HOST";
const char* passcode = "YOUR_PASSCODE";

// Switch pin numbers
#define SW1 5
#define SW2 4
int switch1, switch2;

// Wake from sleep, in seconds.
#define wakeuptime 30

void setup() {
  Serial.begin(115200);
  delay(10);
  Serial.println();
  Serial.println();
  Serial.print("Connecting to ");
  Serial.println(ssid);
  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("");
  Serial.println("WiFi connected");
  Serial.println("IP address: ");
  Serial.println(WiFi.localIP());

  pinMode(SW1, INPUT_PULLUP);
  pinMode(SW2, INPUT_PULLUP);
  
  delay(5000);

  // Connect to host
  Serial.print("Connecting to ");
  Serial.println(host);
  // Use WiFiClient class to create TCP connections
  WiFiClient client;
  const int httpPort = 80;
  if (!client.connect(host, httpPort)) {
    Serial.println("Connection failed!");
    return;
  }

  // Read switch values
  switch1 = digitalRead(SW1);
  switch2 = digitalRead(SW2);
  
  // Create a URL for the request. Modify YOUR_HOST_DIRECTORY so that you're pointing to the PHP file.
  String url = "/YOUR_HOST_DIRECTORY/index.php?s1=";
  url += switch1;
  url += "&s2=";
  url += switch2;
  url += "&pass=";
  url += passcode;

  // This will send the request to the server
  Serial.print("Requesting URL: ");
  Serial.println(url);
  client.print(String("GET ") + url + " HTTP/1.1\r\n" +
               "Host: " + host + "\r\n" +
               "Connection: close\r\n\r\n");
  unsigned long timeout = millis();
  while (client.available() == 0) {
    if (millis() - timeout > 5000) {
      Serial.println(">>> Client Timeout !");
      client.stop();
      return;
    }
  }
/*
  // Read all the lines of the reply from server and print them to Serial
  while (client.available()) {
    String line = client.readStringUntil('\r');
    Serial.print(line);
  }
  */

  Serial.println();
  Serial.println("Closing connection");
  
  // Sleep
  Serial.println("Going to sleep");
  delay(5000);
  ESP.deepSleep(wakeuptime * 1000000, WAKE_RF_DEFAULT);
  delay(5000);
}

void loop() {
}

