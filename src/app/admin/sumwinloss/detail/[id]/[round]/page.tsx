 
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
import useSWR from "swr";
//import {useRouter} from 'next/router'
import { useRouter,useParams } from 'next/navigation';

import Banktransfer from 'components/admin/dashboards/default/Banktransfer';
import NewCustomer from 'components/admin/dashboards/default/Newcutomer';
import TransactionTable from 'components/admin/dashboards/default/TransactionsTable';
import WinlossTable from 'components/admin/dashboards/default/WinlossTable';
//import SumWinlossDetail from 'components/admin/dashboards/default/SumWinlossDetail';
//import SumGameDetail from 'components/admin/dashboards/default/SumGameDetail';
import dynamic from 'next/dynamic'
//import SumWinlossDetail from 'components/admin/dashboards/default/SumWinlossDetail';
//const SumWinlossDetail = dynamic(() => import('components/admin/dashboards/default/SumWinlossDetail'), { ssr: false })
const SumGameDetail = dynamic(() => import('components/admin/dashboards/default/SumGameDetail'), { ssr: false })
 
const fetcher = (url:string) => fetch(url,{ method: 'GET',
  headers: {
  'Accept': 'application/json',
  'Content-Type': 'application/json',
  'Authorization': 'Bearer ' +  localStorage.getItem('token')
  },
// body: raw
}).then((res) => res.json());


async function getData(round:string){
  const token =  ""
  const raw = JSON.stringify({"startdate":new Date(new Date().setDate(new Date().getDate() - 7)).toJSON().slice(0, 10),"stopdate":new Date(new Date().setDate(new Date().getDate() + 7)).toJSON().slice(0, 10),"prefix":"all","statement_type":"all","status":"all","userid":round})
  const  res = await fetch(`https://report.tsxbet.net/reports/winloss/game/${round}`, { cache: 'no-store',method: 'POST',
    headers: {
    'Accept': 'application/json',
    'Content-Type': 'application/json',
    'Authorization': 'Bearer ' 
    },
    body:   raw

  });
  return  res.json();  
}


export default async function Page({ params }: { params: {round: string } }){
  // Chakra Color Mode
  //const paleGray = useColorModeValue('secondaryGray.400', 'whiteAlpha.100');
  // const [TopUps, setTopUps] = React.useState([]);

  // const [TopUpm, setTopUpm] = React.useState([]);
  // const [WinLoss,setWinLoss] = React.useState([])
  // const [Profit,setProfit] = React.useState([{"name":"","sum":0}])
  // const [newusers, setnewusers] = React.useState(0);
  //const router = useRouter();
  //const params = useParams();
  //const {round} = params
  
  
  const data = await getData(params.round)
//   const { data, error, isLoading } = useSWR(
//     "https://report.tsxbet.net/reports",
//     fetcher
//   );
 
// if (error) return An error has occurred.`;
// if (isLoading) return `Loading...`;
// if(!isLoading){
// //  setProfit(data)
// }
  return (
    <div className="flex items-center gap-2"> 
    <div  style={{paddingTop:80+'px'}}>
    <div style={{marginBottom: 20 + 'px'}} >
        <SumGameDetail  transfers={data} />  
   </div>
   </div>
   </div>
  );
}
