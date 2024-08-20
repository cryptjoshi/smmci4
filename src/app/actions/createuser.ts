'use server';

import { randomUUID } from 'crypto';
import { redirect } from 'next/navigation';
import {cookies} from 'next/headers' 



export const SignInAction = async (username:string,password:string)=>{

  try {
    
    const raw = JSON.stringify({
    "username": username,
    "password": password
});
 
 
  const response = await fetch('https://wallet.tsxbet.net/api/Admin/Login', { method: 'POST',
  headers: {
    'Accept': 'application/json',
    'Content-Type': 'application/json'
  },
  body: raw
});
 
  if (!response.ok) {
    throw new Error('Failed to fetch data');
  }
  const result = await response.json()
  //console.log(result)
  const { accessToken, role,prefix } = result.data

  

  // cookies().set('token',accessToken)
  // cookies().set('isLogged',result.status)
  // cookies().set('role',role)

  return result
  
 
}
catch(err){
   
  return err
}
}
 