'use client';
import React, { ReactNode } from 'react';
import 'styles/App.css';
import 'styles/Contact.css';
import 'styles/MiniCalendar.css';
import { ChakraProvider } from '@chakra-ui/react';
import { CacheProvider } from '@chakra-ui/next-js';
import theme from 'theme/theme';


interface AppWrappersProps {
  role: string;
  children: ReactNode;
}

export default function AppWrappers<AppWrappersProps>({ role,children }: { role:string,children: ReactNode }) {
 
  return (
    <CacheProvider>
      <ChakraProvider theme={theme} >
      {React.Children.toArray(children).map((child:any) => 
              React.isValidElement(child) ? React.cloneElement(child, role) : child
            )}
        </ChakraProvider> 
    </CacheProvider>
  );
}

 
