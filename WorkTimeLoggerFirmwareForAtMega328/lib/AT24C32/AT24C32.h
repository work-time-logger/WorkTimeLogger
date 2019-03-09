//
// Created by Krystian Duma on 2019-02-27.
//

#ifndef WORKTIMELOGGERFIRMWAREFORATMEGA328_AT24C32_H
#define WORKTIMELOGGERFIRMWAREFORATMEGA328_AT24C32_H

#include <Arduino.h>

void i2c_eeprom_write_byte( int deviceaddress, unsigned int eeaddress, byte data );
void i2c_eeprom_write_page( int deviceaddress, unsigned int eeaddresspage, byte* data, byte length );
byte i2c_eeprom_read_byte( int deviceaddress, unsigned int eeaddress );
void i2c_eeprom_read_buffer( int deviceaddress, unsigned int eeaddress, byte *buffer, int length );

#endif //WORKTIMELOGGERFIRMWAREFORATMEGA328_AT24C32_H
