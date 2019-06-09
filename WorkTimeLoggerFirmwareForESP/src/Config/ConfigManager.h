// Config/ConfigManager.h

#ifndef CONFIGMANAGER_H
#define CONFIGMANAGER_H

#include <IPAddress.h>

void CONFIG_INIT();
void CONFIG_FORCE();

char * CONFIG_GET_SERVER();
char * CONFIG_GET_TOKEN();
char * CONFIG_GET_OTA_PASSWORD();


#endif //CONFIGMANAGER_H
