import { Box } from '@chakra-ui/react';
import React, { ReactNode } from 'react';
import AppWrappers from './AppWrappers';
import {cookies} from 'next/headers'
import Switcher from 'components/Switcher';
import {NextIntlClientProvider} from 'next-intl';
// import {getMessages} from 'next-intl/server';
export default  function RootLayout({ children,params: {locale} }: { children: ReactNode,params:any }) {
  //const role = cookies().get('user-role')?.value || "guest";
 //const messages = await getMessages()
  return (
    <html lang={"th"} suppressHydrationWarning={true}>
      <body id={'root'} suppressHydrationWarning={true}>
      {/* <NextIntlClientProvider messages={messages}> */}
        {/* <Switcher /> */}
        <AppWrappers >{children}</AppWrappers>
        {/* </NextIntlClientProvider> */}
      </body>
    </html>
  );
}
