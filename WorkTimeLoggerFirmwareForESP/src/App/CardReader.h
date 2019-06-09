// App/CardReader.h

#ifndef CARDREADER_H
#define CARDREADER_H

#include <Modules/WebApi.h>

void CARDREADER_INIT();
void CARDREADER_EVENT();
extern char last_read_card[50];
extern QueryResponse last_query_response;

#endif //CARDREADER_H
