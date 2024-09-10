//@ts-nocheck
'use client';
import { cookies } from 'next/headers';
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
import useSWR from "swr";
 
import { useRouter,redirect } from 'next/navigation';
import Banktransfer from 'components/admin/dashboards/default/Banktransfer';
import NewCustomer from 'components/admin/dashboards/default/Newcutomer';
import { getAccount, getIsLogged, getToken } from 'app/actions/userInfof';
import PageContent from 'components/PageContent';
import { getSession, getUserstatus } from 'app/actions/auth';
 
const fetcher = (url:string) => fetch(url,{ method: 'POST',
  headers: {
  'Accept': 'application/json',
  'Content-Type': 'application/json',
  'Authorization': 'Bearer ' +  localStorage.getItem('token')
  },
 body: JSON.stringify({"prefix": getSession().then((session)=>session)})
}).then((res) => res.json());


async function getData(){
  const token = localStorage.getItem('token');
  const isLogged = localStorage.getItem('isLogged')
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
  
  
  const [data, setData] = React.useState(null)
 
  useEffect(() => {
    async function fetchPosts() {
      let session = await getSession()
      let res = await fetch('https://report.tsxbet.net/reports/count/userstatus',{ method: 'POST',
      headers: {
      'Accept': 'application/json',
      'Content-Type': 'application/json',
      'Authorization': 'Bearer ' +  session.accessToken
      },
    body: JSON.stringify({"prefix":session.prefix})
    })
      let data = await res.json()
      setData(data)
    }
    fetchPosts()
  }, [])
 
  if (!data) return <div>Loading...</div>
 

  return (
    <PageContent title="">
    <Flex
      direction={{ base: 'column', xl: 'row' }}
      pt={{ base: '130px', md: '80px', xl: '80px' }}
    >
      <Flex direction="column" width="stretch">
        <Grid
          mb="20px"
          gridTemplateColumns={{ base: 'repeat(2, 1fr)', '2xl': '720fr 350fr' }}
          gap="20px"
          display={{ base: 'block', lg: 'grid' }}
        >
          <Flex gridArea={{ base: '1 / 1 / 2 / 3', '2xl': '1 / 1 / 2 / 2' }}>
            <Banktransfer />
          </Flex>
          <Flex gridArea={{ base: '2 / 1 / 3 / 3', '2xl': '1 / 2 / 2 / 3' }}>
            <Balance />
          </Flex>
        </Grid>
        <Grid
           mb="20px"
           gridTemplateColumns={{ base: 'repeat(2, 1fr)', '2xl': '720fr 350fr' }}
           gap="20px"
           display={{ base: 'block', lg: 'grid' }}
        >
          {/* <Flex gridArea={{ md: '1 / 1 / 2 / 2', '2xl': '1 / 1 / 2 / 2' }}>
            <DailyTraffic />
          </Flex> */}
          <Flex gridArea={{ base: '1 / 1 / 2 / 3', '2xl': '1 / 1 / 2 / 2' }}>
          <OverallRevenue />
          </Flex>
          <Flex gridArea={{ base: '2 / 1 / 3 / 3', '2xl': '1 / 2 / 2 / 3' }}>
              <ProfitEstimation  {...data} />
          </Flex>
        </Grid>
        <Grid
        mb={"20px"}
          templateColumns={{ base: 'repeat(2, 1fr)', '2xl': '350fr 720fr' }}
          gap="20px"
          display={{ base: 'block', lg: 'grid' }}
        >
          <Flex gridArea={{ base: '1 / 1 / 2 / 3', '2xl': '1 / 1 / 2 / 2' }}>
             <YourTransfers /> 
          </Flex>
          <Flex gridArea={{ base: '2 / 1 / 3 / 3', '2xl': '1 / 2 / 2 / 3' }}>
            <MostVisitedTable tableData={tableDataMostVisited} />
          </Flex>
        </Grid>
        <Grid
          mb="20px"
          gridTemplateColumns={{ base: 'repeat(2, 1fr)', '2xl': '100%' }}
          gap="20px"
          display={{ base: 'block', lg: 'grid' }}
        >
          <Flex gridArea={{ base: '1 / 1 / 2 / 3', '2xl': '1 / 1 / 2 / 2' }}>
             <NewCustomer tableData={tableDataMostVisited} />
          </Flex>
        </Grid>
      </Flex>
      <VSeparator
        mx="20px"
        bg={paleGray}
        display={{ base: 'none', xl: 'flex' }}
      />
      <YourCard
        maxW={{ base: '100%', xl: '400px' }}
        maxH={{ base: '100%', xl: '1170px', '2xl': '100%' }}
      />
    </Flex>
    </PageContent>
  );
}
