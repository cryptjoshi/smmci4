//'use client';

// Chakra imports
//import { Flex, Grid, useColorModeValue } from '@chakra-ui/react';
// Custom components
import Balance from 'components/admin/dashboards/default/Balance';
import DailyTraffic from 'components/admin/dashboards/default/DailyTraffic';
import MostVisitedTable from 'components/admin/dashboards/default/MostVisitedTable';
import OverallRevenue from 'components/admin/dashboards/default/OverallRevenue';
import ProjectStatus from 'components/admin/dashboards/default/ProjectStatus';
import YourCard from 'components/admin/dashboards/default/YourCard';
import { VSeparator } from 'components/separator/Separator';
import YourTransfers from 'components/admin/dashboards/default/YourTransfers';
import tableDataMostVisited from 'variables/dashboards/default/tableDataMostVisited';
import ProfitEstimation from 'components/admin/dashboards/default/ProfitEstimation';
import React from 'react';
import { useEffect } from 'react';
import useSWR from "swr";0
//import {useRouter} from 'next/router'
import { useRouter,useParams } from 'next/navigation';
//import { useRouter } from 'next/navigation';
import Banktransfer from 'components/admin/dashboards/default/Banktransfer';
import NewCustomer from 'components/admin/dashboards/default/Newcutomer';
import TransactionTable from 'components/admin/dashboards/default/TransactionsTable';
import WinlossTable from 'components/admin/dashboards/default/WinlossTable';
//import SumWinlossDetail from 'components/admin/dashboards/default/SumWinlossDetail';
import dynamic from 'next/dynamic'
//import SumWinlossDetail from 'components/admin/dashboards/default/SumWinlossDetail';
const SumWinlossDetail = dynamic(() => import('components/admin/dashboards/default/SumWinlossDetail'), { ssr: false })
 
const fetcher = (url:string) => fetch(url,{ method: 'GET',
  headers: {
  'Accept': 'application/json',
  'Content-Type': 'application/json',
  'Authorization': 'Bearer ' +  "" //localStorage.getItem('token')
  },
// body: raw
}).then((res) => res.json());


// async function safeFetchData(url:string) {
//   try {
//       const response = await fetch(url);
//       if (!response.ok) {
//           throw new Error('Network response was not ok');
//       }
//       return await response.json();
//   } catch (error) {
//     //@ts-ignore
//       if (error instanceof NetworkError) {
//           // handle network error
//           //@ts-ignore
//       } else if (error instanceof ApiError) {
//           // handle API-specific error
//       } else {
//           // handle generic errors
//       }
//       // Possibly return a fallback value or re-throw the error
//   }
// }

async function getData(id:string){
 // console.log(`https://report.tsxbet.net/reports/sumwinloss/${id}`)
 // console.log( JSON.stringify({"startdate":new Date(new Date().setDate(new Date().getDate() - 7)).toJSON().slice(0, 10),"stopdate":new Date(new Date().setDate(new Date().getDate() + 7)).toJSON().slice(0, 10),"prefix":"all","statement_type":"all","status":"all"}))
  const token = ""//localStorage.getItem('token');
  const  res = await fetch(`https://report.tsxbet.net/reports/sumwinloss/${id}`, {   cache: 'no-store'  ,method: 'POST',
    headers: {
    'Accept': 'application/json',
    'Content-Type': 'application/json',
    'Authorization': 'Bearer ' +  token
    },
    body: JSON.stringify({"startdate":new Date(new Date().setDate(new Date().getDate() - 7)).toJSON().slice(0, 10),"stopdate":new Date(new Date().setDate(new Date().getDate() + 7)).toJSON().slice(0, 10),"prefix":"all","statement_type":"all","status":"all"})
    
  });
  return  res.json();  
}


export default async function  Page({ params }: { params: { id: string } }){
  // Chakra Color Mode
  //  const paleGray = useColorModeValue('secondaryGray.400', 'whiteAlpha.100');
  // // const [TopUps, setTopUps] = React.useState([]);

  // // const [TopUpm, setTopUpm] = React.useState([]);
  // // const [WinLoss,setWinLoss] = React.useState([])
  // const [Profit,setProfit] = React.useState([{"name":"","sum":0}])
  // // const [newusers, setnewusers] = React.useState(0);
  // const router = useRouter();
 // const params = useParams();
 
    const {id} = params
 
    const data = await getData(id)
   
  // const { data, error, isLoading } = useSWR(
  //   "https://report.tsxbet.net/reports",
  //   fetcher
  // );
  

// if (error) return An error has occurred.`;
// if (isLoading) return `Loading...`;
// if(!isLoading){
// //  setProfit(data)
// }
  return (
    <div className="flex items-center gap-2  ">
    <div  style={{paddingTop:80+'px'}}>
    <div style={{marginBottom: 20 + 'px'}}
    >
          <SumWinlossDetail transfers={data}  />
         </div>
      </div>
      </div>
  );
}
