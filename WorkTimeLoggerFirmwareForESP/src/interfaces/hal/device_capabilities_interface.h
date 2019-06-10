#ifndef DEVICE_CAPABILITIES_H
#define DEVICE_CAPABILITIES_H

struct device_capabilities
{
    char name[25];
    char wifi_name[45];
    char wifi_password[25];
    bool hmi;
};

#endif //DEVICE_CAPABILITIES_H
