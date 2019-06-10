#ifndef RFID_READER_INTERFACE_H
#define RFID_READER_INTERFACE_H

typedef void (*rfid_reader_init)();
typedef void (*rfid_reader_event)();
typedef void (*rfid_reader_set_callback)(void (*callback)(char read_card[]));

struct rfid_reader_interface
{
    rfid_reader_init init;
    rfid_reader_event event;
    rfid_reader_set_callback set_callback;
};

#endif //RFID_READER_INTERFACE_H
