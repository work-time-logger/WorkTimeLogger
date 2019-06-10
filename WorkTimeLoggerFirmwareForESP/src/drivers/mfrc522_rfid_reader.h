#ifndef MFRC522_RFID_READER_H
#define MFRC522_RFID_READER_H

#define RST_PIN D4
#define SS_PIN D8

#include "interfaces/drivers/rfid_reader_interface.h"

const struct rfid_reader_interface *mfrc522_rfid_reader_get();

#endif //MFRC522_RFID_READER_H
