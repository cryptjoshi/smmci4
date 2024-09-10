import { Box } from '@chakra-ui/react';
import React, { ReactNode } from 'react';
import AppWrappers from './AppWrappers';
import {cookies} from 'next/headers'
import Switcher from 'components/Switcher';
import {NextIntlClientProvider} from 'next-intl';
import { ViewTransitions } from 'next-view-transitions';
import { kanit } from 'utils/font';
// import {getMessages} from 'next-intl/server';
export default  function RootLayout({ children,params: {locale} }: { children: ReactNode,params:any }) {
  //const role = cookies().get('user-role')?.value || "guest";
 //const messages = await getMessages()
  return (
    <ViewTransitions>    
    <html lang={"th"} suppressHydrationWarning={true}>
      <body id={'root'} suppressHydrationWarning={true} className={kanit.className}>
      {/* <NextIntlClientProvider messages={messages}> */}
        {/* <Switcher /> */}
        <AppWrappers role={""}>{children}</AppWrappers>
        {/* </NextIntlClientProvider> */}
      </body>
    </html>
    </ViewTransitions>
  );
}
