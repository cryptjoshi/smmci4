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
  const  res = await fetch('http://128.199.92.45:3003/reports/count/userstatus', { method: 'POST',
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
    "http://128.199.92.45:3003/reports/count/userstatus",
    fetcher
  );
 
//   useEffect(() => {
//     const checkData = async () => {
//         const token = localStorage.getItem('token');
       
//         if(!token){
//           router.replace('/auth/sign-in')
//       } else 
//       {
//     //         const today = new Date().toJSON().slice(0, 10);
//     //         const raw = JSON.stringify({"startdate":today,"stopdate":today,"prefix":"all"});
//     //         let data;
//     //         let res = await fetch('http://128.199.92.45:3003/reports/count/topups', { method: 'POST',
//     //             headers: {
//     //               'Accept': 'application/json',
//     //               'Content-Type': 'application/json',
//     //               'Authorization': 'Bearer ' +  token
//     //             },
//     //         body: raw
//     //         });
//     //         data = await res.json();
//     //        // console.log(data)
//     //         if(data.status){
//     //             setTopUps(data.data)
//     //            // setAuthenticated(true)
//     //         } else {
//     //          // router.replace('/auth/sign-in')
//     //         } 

           
//     //          res = await fetch('http://128.199.92.45:3003/reports/count/newusers', { method: 'POST',
//     //             headers: {
//     //               'Accept': 'application/json',
//     //               'Content-Type': 'application/json',
//     //               'Authorization': 'Bearer ' +  token
//     //             },
//     //         body: raw
//     //         });
//     //         data = await res.json();0
//     //        // console.log(data)
//     //         if(data.status){
//     //             setnewusers(data.data)
//     //            // setAuthenticated(true)
//     //         } else {
//     //          // router.replace('/auth/sign-in')
//     //         } 
//     //         res = await fetch('http://128.199.92.45:3003/reports/count/topupm', { method: 'POST',
//     //         headers: {
//     //           'Accept': 'application/json',
//     //           'Content-Type': 'application/json',
//     //           'Authorization': 'Bearer ' +  token
//     //         },
//     //     body: raw
//     //     });
//     //     data = await res.json();
//     //    // console.log(data)
//     //     if(data.status){
//     //         setTopUpm(data.data)
//     //        // setAuthenticated(true)
//     //     } else {
//     //       //router.replace('/auth/sign-in')
//     //     } 

//     //     res = await fetch('http://128.199.92.45:3003/reports/winloss', { method: 'POST',
//     //       headers: {
//     //         'Accept': 'application/json',
//     //         'Content-Type': 'application/json',
//     //         'Authorization': 'Bearer ' +  token
//     //       },
//     //   body: raw
//     //   });
//     //   data = await res.json();
//     //  // console.log(data)
//     //   if(data.status){
//     //       setWinLoss(data.data)
//     //      // setAuthenticated(true)
//     //   } else {
//     //   //  router.replace('/auth/sign-in')
//     //   } 

    

//           }
//     }
//     checkData()
// },[])

if (error) return "An error has occurred.";
if (isLoading) return "Loading...";
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
            <ProfitEstimation Profit={data} />
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
  );
}
