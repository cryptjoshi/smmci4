'use client';

// Chakra imports
import { Flex, Grid, useColorModeValue } from '@chakra-ui/react';
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
 
import { useRouter } from 'next/navigation';
import Banktransfer from 'components/admin/dashboards/default/Banktransfer';
import NewCustomer from 'components/admin/dashboards/default/Newcutomer';
import TransactionTable from 'components/admin/dashboards/default/TransactionsTable';

 
const fetcher = (url:string) => fetch(url,{ method: 'POST',
  headers: {
  'Accept': 'application/json',
  'Content-Type': 'application/json',
  'Authorization': 'Bearer ' +  localStorage.getItem('token')
  },
// body: raw
}).then((res) => res.json());


async function getData(){
  const token = localStorage.getItem('token');
  const  res = await fetch('https://report.tsxbet.net/reports/count/userstatus', { method: 'POST',
    headers: {
    'Accept': 'application/json',
    'Content-Type': 'application/json',
    'Authorization': 'Bearer ' +  token
    },
 // body: raw
  });
  return  res.json();  
}


export default function Page() {
  // Chakra Color Mode
  const paleGray = useColorModeValue('secondaryGray.400', 'whiteAlpha.100');
  // const [TopUps, setTopUps] = React.useState([]);

  // const [TopUpm, setTopUpm] = React.useState([]);
  // const [WinLoss,setWinLoss] = React.useState([])
  const [Profit,setProfit] = React.useState([{"name":"","sum":0}])
  // const [newusers, setnewusers] = React.useState(0);
  const router = useRouter()
  const { data, error, isLoading } = useSWR(
    "https://report.tsxbet.net/reports/count/userstatus",
    fetcher
  );
  

if (error) return <>"An error has occurred."</>;
if (isLoading) return <>"Loading..."</>;
if(!isLoading){
//  setProfit(data)
}
  return (
    <Flex
      direction={{ base: 'column', xl: 'row' }}
      pt={{ base: '130px', md: '80px', xl: '80px' }}
    >
      <Flex direction="column" width="stretch">
     
      
        <Grid
          mb="20px"
          gridTemplateColumns={{ base: 'repeat(2, 1fr)', '2xl': '100%' }}
          gap="20px"
          display={{ base: 'block', lg: 'grid' }}
        >
          <Flex gridArea={{ base: '1 / 1 / 2 / 3', '2xl': '1 / 1 / 2 / 2' }}>
          <TransactionTable tableData={tableDataMostVisited} />
          </Flex>
        </Grid>
      </Flex>
    </Flex>
  );
}
