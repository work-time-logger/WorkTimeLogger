//#include <Wire.h>
//#include <Arduino.h>
//#include <AT24C32.h>
//#include "config.h"
//
//#include <RTClib.h>
//RTC_DS1307 RTC;
//
//#include <LiquidCrystal_PCF8574.h>
//
//LiquidCrystal_PCF8574 lcd(0x27);  // set the LCD address to 0x27 for a 16 chars and 2 line display
//
//void setup()
//  {
//    int addr=0,i;
//    Wire.begin(); // initialise the connection
//    Serial.begin(9600);
//
//      Serial.println("Starting");
//      Serial.println(__DATE__);
//      Serial.println(__TIME__);
//      RTC.begin();
//      if (! RTC.isrunning()) {
//          Serial.println("RTC is NOT running!");
//          // following line sets the RTC to the date & time this sketch was compiled
//          RTC.adjust(DateTime(__DATE__, __TIME__));
//      }
//
//      int error;
//
//      Serial.println("Dose: check for LCD");
//
//      // See http://playground.arduino.cc/Main/I2cScanner
//      Wire.beginTransmission(0x27);
//      error = Wire.endTransmission();
//      Serial.print("Error: ");
//      Serial.print(error);
//
//      if (error == 0) {
//          Serial.println(": LCD found.");
//
//      } else {
//          Serial.println(": LCD not found.");
//      } // if
//
//      lcd.begin(16, 2); // initialize the lcd
//  }
//
//  void loop()
//  {
//    int i,b;
//    int addr=0; //first address
//    Serial.println("Read:");
//    for(i=0;i<26;i++)
//    {
//      b = i2c_eeprom_read_byte(AT24C32_ADDRESS, i); //access an address from the memory
//      Serial.print(char(b));
//  }
//     Serial.print('\n');
//
//      DateTime now = RTC.now();
//      Serial.print(now.year(), DEC);
//      Serial.print('/');
//      Serial.print(now.month(), DEC);
//      Serial.print('/');
//      Serial.print(now.day(), DEC);
//      Serial.print(' ');
//      Serial.print(now.hour(), DEC);
//      Serial.print(':');
//      Serial.print(now.minute(), DEC);
//      Serial.print(':');
//      Serial.print(now.second(), DEC);
//      Serial.println();
//
//      lcd.setBacklight(255);
//      lcd.home(); lcd.clear();
//      lcd.print(now.year(), DEC);
//      lcd.print('/');
//      lcd.print(now.month(), DEC);
//      lcd.print('/');
//      lcd.print(now.day(), DEC);
//      lcd.setCursor(0, 1);
//      lcd.print(now.hour(), DEC);
//      lcd.print(':');
//      lcd.print(now.minute(), DEC);
//      lcd.print(':');
//      lcd.print(now.second(), DEC);
//
//
//      delay(1000);
//  }
//
