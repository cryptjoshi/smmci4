'use server';

import { randomUUID } from 'crypto';
import { redirect } from 'next/navigation';
import { cookies } from 'next/headers';



 
// const fetcher = (url:string) => fetch(url,{ method: 'POST',
//   headers: {
//   'Accept': 'application/json',
//   'Content-Type': 'application/json',
//   'Authorization': 'Bearer ' +  localStorage.getItem('token')
//   },
// // body: raw
// }).then((res) => res.json());


// async function getData(){
//   const token = localStorage.getItem('token');
//   const isLogged = localStorage.getItem('isLogged')
//   const  res = await fetch('https://report.tsxbet.net/reports/count/userstatus', { method: 'POST',
//     headers: {
//     'Accept': 'application/json',
//     'Content-Type': 'application/json',
//     'Authorization': 'Bearer ' +  token
//     },
//  // body: raw
//   });
//   return  res.json();  
// }

export const cleatToken = async ()=>{
  await cookies().set('token','')
}
export const getToken = async () => {
 
  return  "";//cookies().get('token') & await cookies().get('token').value;
 // const token = localStorage.getItem('token');
 // const isLogged = localStorage.getItem('isLogged')
//   const  res = await fetch('https://report.tsxbet.net/reports/count/userstatus', { method: 'POST',
//     headers: {
//     'Accept': 'application/json',
//     'Content-Type': 'application/json',
//     'Authorization': 'Bearer ' +  token
//     },
//  // body: raw
//   });
//   return  res.json();  
 
};
export const getIsLogged = async () => {
  return await cookies().get('isLogged').value;
}

export const setToken = async  (val:string) => {
 
  return await cookies().set('token',val);
}

export const setIsLogged = async (val:boolean) =>{
  return await cookies().set('IsLogged',val)
}
export const getAccount = async () => {
//   const username = formData.get('username');
//   const email = formData.get('email');
//   const password = formData.get('password');
// console.log(formData)
  // if (!username  || !password) {
  //   return {
  //     message: 'Please fill all fields',
  //     success: false,
  //   };
  // }
  const token = cookies().get('token');
  // const token = localStorage.getItem('token');
  // const isLogged = localStorage.getItem('isLogged')
   const  res = await fetch('https://report.tsxbet.net/reports/count/userstatus', { method: 'POST',
     headers: {
     'Accept': 'application/json',
     'Content-Type': 'application/json',
     'Authorization': 'Bearer ' +  token
     },
  // body: raw
   });
 
  
return  await res.json();
//   if(data.status){
//   cookies().set('token', data.message);
//   cookies().set('isLogged',true)
//   redirect('/admin/default');
//   } 
//   // else {
//   //   cookies().set('token', "");
//   //   cookies().set('isLogged',false)
//   // }
};