#ifndef _DEBUG_H
#define _DEBUG_H

#ifdef DEBUG_APP
    #ifdef DEBUG_PORT
        #define DEBUG_APP(...) DEBUG_PORT.printf( __VA_ARGS__ )
    #else
        #define DEBUG_APP(...)
    #endif
#else
    #define DEBUG_APP(...)
#endif

#ifdef DEBUG_BACKEND
    #ifdef DEBUG_PORT
        #define DEBUG_BACKEND(...) DEBUG_PORT.printf( __VA_ARGS__ )
    #else
        #define DEBUG_BACKEND(...)
    #endif
#else
    #define DEBUG_BACKEND(...)
#endif


#endif //_DEBUG_H
