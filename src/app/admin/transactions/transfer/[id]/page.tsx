// import {
//     Badge,
//     Box,
//     Flex,
//     Grid,
//     Button,
//     Icon,
//     Link,
//     Table,
//     Tbody,
//     Td,
//     Th,
//     Thead,
//     Tr,
//     useColorModeValue,
//     HStack,
//     Stack,
//     Heading,
//     AbsoluteCenter,
//     Divider,
//     FormControl,
//     FormLabel,
//     Input,
//     InputGroup,
//     InputRightElement,

// } from '@chakra-ui/react'

// import { HSeparator } from 'components/separator/Separator';

// export default function transfer() {
   
//     // Chakra Color Mode
//     const paleGray = useColorModeValue('secondaryGray.400', 'whiteAlpha.100');
//     // const [TopUps, setTopUps] = React.useState([]);
  
//     // const [TopUpm, setTopUpm] = React.useState([]);
//     // const [WinLoss,setWinLoss] = React.useState([])
//     const [details,setDetails] = React.useState([{"name":"","sum":0}])
//     // const [newusers, setnewusers] = React.useState(0);
//     const router = useRouter();

// return (
//     <Flex
//       direction={{ base: 'column', xl: 'row' }}
//       pt={{ base: '130px', md: '80px', xl: '80px' }}
//     >
//       <Flex direction="column" width="stretch">
//         <Grid
//           mb="20px"
//           gridTemplateColumns={{ base: 'repeat(2, 1fr)', '2xl': '100%' }}
//           gap="20px"
//           display={{ base: 'block', lg: 'grid' }}
//         >
//           <Flex gridArea={{ base: '1 / 1 / 2 / 3', '2xl': '1 / 1 / 2 / 2' }}>
//           //    <Box
//                 rounded={'lg'}
//                 bg={useColorModeValue('white', 'gray.700')}
//                 boxShadow={'lg'}
//                 p={8}>
//                 <Stack pt={5}>
//                 <HSeparator />
//                     {/* <Text color="gray.400" mx="14px">
//                     or
//                     </Text>
//                     <HSeparator /> */}
//                 </Stack>
//                 <Stack spacing={10} pt={2}>
                
//                 <HStack>
//                 <FormControl id="amount" isRequired pb={5}>
//                     <FormLabel>ยอดเงิน</FormLabel>
//                     <Input type="text"  name="amount"/>
//                 </FormControl>
//                 </HStack>
//                 </Stack>
//                 <HStack w={"100%"}>
                    
//                 </HStack>

//             </Box>
//           </Flex>
//         </Grid>
//       </Flex>
//     </Flex>
//   );
// }

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
//import {useRouter} from 'next/router'
import { useRouter } from 'next/navigation';
import Banktransfer from 'components/admin/dashboards/default/Banktransfer';
import NewCustomer from 'components/admin/dashboards/default/Newcutomer';
import TransactionTable from 'components/admin/dashboards/default/TransactionsTable';
import WinlossTable from 'components/admin/dashboards/default/WinlossTable';
import WinlossDetail from 'components/admin/dashboards/default/WinlossDetail';
import MembersDetail from 'components/admin/dashboards/default/MembersDetail';
import StatementsDetail from 'components/admin/dashboards/default/StatementDetail';

 
const fetcher = (url:string) => fetch(url,{ method: 'GET',
  headers: {
  'Accept': 'application/json',
  'Content-Type': 'application/json',
  'Authorization': 'Bearer ' +  localStorage.getItem('token')
  },
// body: raw
}).then((res) => res.json());


// async function getData(){
//   const token = localStorage.getItem('token');
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


export default function Page() {
  // Chakra Color Mode
  const paleGray = useColorModeValue('secondaryGray.400', 'whiteAlpha.100');
  // const [TopUps, setTopUps] = React.useState([]);

  // const [TopUpm, setTopUpm] = React.useState([]);
  // const [WinLoss,setWinLoss] = React.useState([])
  const [Profit,setProfit] = React.useState([{"name":"","sum":0}])
  // const [newusers, setnewusers] = React.useState(0);
  const router = useRouter();
  

//   const { data, error, isLoading } = useSWR(
//     "https://report.tsxbet.net/reports",
//     fetcher
//   );
  

// if (error) return "An error has occurred.";
// if (isLoading) return "Loading...";
// if(!isLoading){
// //  setProfit(data)
// }
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
        <StatementsDetail />
          </Flex>
        </Grid>
      </Flex>
    </Flex>
  );
}
