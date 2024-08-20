'use client';

import {
  Box,
  Flex,
  FormLabel,
  Image,
  Icon,
  Select,
  SimpleGrid,
  useColorModeValue,
  Grid,
} from '@chakra-ui/react';
// Custom components
// import MiniCalendar from 'components/calendar/MiniCalendar';
import MiniStatistics from 'components/card/MiniStatistics';
import IconBox from 'components/icons/IconBox';
import {
  MdAddTask,
  MdAttachMoney,
  MdDiversity3,
  MdGroupAdd,
  Md10K,
  MdBarChart,
  MdFileCopy,
} from 'react-icons/md';
import CheckTable from 'views/admin/default/components/CheckTable';
import ComplexTable from 'views/admin/default/components/ComplexTable';
import DailyTraffic from 'views/admin/default/components/DailyTraffic';
import PieCard from 'views/admin/default/components/PieCard';
import Tasks from 'views/admin/default/components/Tasks';
import TotalSpent from 'views/admin/default/components/TotalSpent';
import WeeklyRevenue from 'views/admin/default/components/WeeklyRevenue';
import tableDataCheck from 'views/admin/default/variables/tableDataCheck';
import tableDataComplex from 'views/admin/default/variables/tableDataComplex';
import Balance from 'components/admin/dashboards/default/Balance';
import MostVisitedTable from 'components/admin/dashboards/default/MostVisitedTable';
import OverallRevenue from 'components/admin/dashboards/default/OverallRevenue';
import ProjectStatus from 'components/admin/dashboards/default/ProjectStatus';
import YourCard from 'components/admin/dashboards/default/YourCard';
import { VSeparator } from 'components/separator/Separator';
import YourTransfers from 'components/admin/dashboards/default/YourTransfers';
import tableDataMostVisited from 'variables/dashboards/default/tableDataMostVisited';
import ProfitEstimation from 'components/admin/dashboards/default/ProfitEstimation';

// Assets
import Usa from 'img/dashboards/usa.png';
import { useEffect } from 'react';
import React from 'react';
import { useRouter } from 'next/navigation';
import { redirect } from 'next/navigation';
import MiniCalendar from 'components/calendar/MiniCalendar';

export default function Default() {
  // Chakra Color Mode
  const paleGray = useColorModeValue('secondaryGray.400', 'whiteAlpha.100');
  const router = useRouter()
  const brandColor = useColorModeValue('brand.500', 'white');
  const boxBg = useColorModeValue('secondaryGray.300', 'whiteAlpha.100');
  const [TopUps, setTopUps] = React.useState([]);

  const [TopUpm, setTopUpm] = React.useState([]);
  const [WinLoss,setWinLoss] = React.useState([])
  const [newusers, setnewusers] = React.useState(0);

  useEffect(() => {
    const checkData = async () => {
        const token = localStorage.getItem('token');
       
        if(!token){
          router.replace('/auth/sign-in')
      } else 
      {
            const today = new Date().toJSON().slice(0, 10);
            const raw = JSON.stringify({"startdate":today,"stopdate":today,"prefix":"all"});
            let data;
            let res = await fetch('https://report.tsxbet.net/reports/count/topups', { method: 'POST',
                headers: {
                  'Accept': 'application/json',
                  'Content-Type': 'application/json',
                  'Authorization': 'Bearer ' +  token
                },
            body: raw
            });
            data = await res.json();
           // console.log(data)
            if(data.status){
                setTopUps(data.data)
               // setAuthenticated(true)
            } else {
              router.replace('/auth/sign-in')
            } 

           
             res = await fetch('https://report.tsxbet.net/reports/count/newusers', { method: 'POST',
                headers: {
                  'Accept': 'application/json',
                  'Content-Type': 'application/json',
                  'Authorization': 'Bearer ' +  token
                },
            body: raw
            });
            data = await res.json();0
           // console.log(data)
            if(data.status){
                setnewusers(data.data)
               // setAuthenticated(true)
            } else {
              router.replace('/auth/sign-in')
            } 
            res = await fetch('https://report.tsxbet.net/reports/count/topupm', { method: 'POST',
            headers: {
              'Accept': 'application/json',
              'Content-Type': 'application/json',
              'Authorization': 'Bearer ' +  token
            },
        body: raw
        });
        data = await res.json();
       // console.log(data)
        if(data.status){
            setTopUpm(data.data)
           // setAuthenticated(true)
        } else {
          router.replace('/auth/sign-in')
        } 

        res = await fetch('https://report.tsxbet.net/reports/winloss', { method: 'POST',
          headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' +  token
          },
      body: raw
      });
      data = await res.json();
     // console.log(data)
      if(data.status){
          setWinLoss(data.data)
         // setAuthenticated(true)
      } else {
        router.replace('/auth/sign-in')
      } 
          }
    }
    checkData()
},[])


  return (
    <Box pt={{ base: '130px', md: '80px', xl: '80px' }}>
      {/* <SimpleGrid
        columns={{ base: 1, md: 2, lg: 3, '2xl': 6 }}
        gap="20px"
        mb="20px"
      >
        <MiniStatistics
          startContent={
            <IconBox
              w="56px"
              h="56px"
              bg={boxBg}
              icon={
                <Icon w="32px" h="32px" as={MdGroupAdd} color={brandColor} />
              }
            />
          }
          name="New Register Today"
          value={newusers.toFixed(0)}
        />
        <MiniStatistics
          startContent={
            <IconBox
              w="56px"
              h="56px"
              bg={boxBg}
              icon={
                <Icon w="32px" h="32px" as={MdBarChart} color={brandColor} />
              }
            />
          }
          name="Register Topup Today "
          value={TopUps.length>0?TopUps[0].first_deposit_amount.toFixed(0):0}
        />
        <MiniStatistics
         startContent={
          <IconBox
            w="56px"
            h="56px"
            bg={boxBg}
            icon={
              <Icon w="32px" h="32px" as={MdAttachMoney} color={brandColor} />
            }
          />
        }
        growth="+23%" name="Topup Money"  value={TopUpm.length>0?TopUpm[0].first_deposit_amount.toFixed(0):0} />
        <MiniStatistics
          endContent={
            <Flex me="-16px" mt="10px">
              <FormLabel htmlFor="balance">
                <Box boxSize={'12'}>
                  <Image alt="" src={Usa.src} w={'100%'} h={'100%'} />
                </Box>
              </FormLabel>
              <Select
                id="balance"
                variant="mini"
                mt="5px"
                me="0px"
                defaultValue="usd"
              >
                <option value="usd">USD</option>
                <option value="eur">EUR</option>
                <option value="gba">GBA</option>
              </Select>
            </Flex>
          }
          name="Win"
          value="$1,000"
        />
         <MiniStatistics
          startContent={
            <IconBox
              w="56px"
              h="56px"
              bg="linear-gradient(90deg, #4481EB 0%, #04BEFE 100%)"
              icon={<Icon w="28px" h="28px" as={MdAddTask} color="white" />}
            />
          }
          name="Win"
          value="154"
        /> 
        <MiniStatistics
          startContent={
            <IconBox
              w="56px"
              h="56px"
              bg="linear-gradient(90deg, #4481EB 0%, #04BEFE 100%)"
              icon={<Icon w="28px" h="28px" as={MdAddTask} color="white" />}
            />
          }
          name="Loss"
          value="154"
        /> 
        <MiniStatistics
          startContent={
            <IconBox
              w="56px"
              h="56px"
              bg={boxBg}
              icon={
                <Icon w="32px" h="32px" as={MdFileCopy} color={brandColor} />
              }
            />
          }
          name="Total Projects"
          value="2935"
        />
      </SimpleGrid> */}
      <Flex direction="column" width="stretch">
        <Grid
          mb="20px"
          gridTemplateColumns={{ base: 'repeat(3, 1fr)', '3xl': '720fr 350fr' }}
          gap="20px"
          display={{ base: 'block', lg: 'grid' }}
        >
          <Flex gridArea={{ base: '1 / 1 / 2 / 3', '3xl': '1 / 1 / 2 / 2' }}>
            <OverallRevenue />
          </Flex>
          <Flex gridArea={{ base: '2 / 1 / 3 / 3', '3xl': '1 / 2 / 2 / 3' }}>
            <Balance />
          </Flex>
          <Flex gridArea={{ base: '3 / 1 / 3 / 3', '3xl': '1 / 2 / 2 / 3' }}>
            <Balance />
          </Flex>
        </Grid>
        <Grid
          mb="20px"
          gridTemplateColumns={{ base: 'repeat(2, 1fr)', '2xl': '720fr 350fr' }}
          gap="20px"
          display={{ base: 'block', lg: 'grid' }}
        >
          <Flex gridArea={{ base: '1 / 1 / 2 / 3', '2xl': '1 / 1 / 2 / 2' }}>
            <OverallRevenue />
          </Flex>
          <Flex gridArea={{ base: '2 / 1 / 3 / 3', '2xl': '1 / 2 / 2 / 3' }}>
          <ProfitEstimation />
          </Flex>
        </Grid>
        <Grid
          gap="20px"
          gridTemplateColumns={{
            md: 'repeat(2, 1fr)',
            '2xl': 'repeat(3, 1fr)',
          }}
          gridTemplateRows={{
            md: 'repeat(2, 1fr)',
            '2xl': '1fr',
          }}
          mb="20px"
        >
          <Flex gridArea={{ md: '1 / 1 / 2 / 2', '2xl': '1 / 1 / 2 / 2' }}>
            <DailyTraffic />
          </Flex>
          <Flex gridArea={{ md: '1 / 2 / 2 / 3', '2xl': '1 / 2 / 2 / 3' }}>
            <ProjectStatus />
          </Flex>
          <Flex gridArea={{ md: ' 2 / 1 / 3 / 3', '2xl': '1 / 3 / 2 / 4' }}>
            <ProfitEstimation />
          </Flex>
        </Grid>
        <Grid
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
      </Flex>

      {/* <SimpleGrid columns={{ base: 1, md: 2, xl: 2 }} gap="20px" mb="20px">
        <TotalSpent />
        <WeeklyRevenue />
      </SimpleGrid> */}
      {/* <SimpleGrid columns={{ base: 1, md: 1, xl: 2 }} gap="20px" mb="20px">
        <CheckTable tableData={tableDataCheck} />
        <SimpleGrid columns={{ base: 1, md: 2, xl: 2 }} gap="20px">
          <DailyTraffic />
          <PieCard />
        </SimpleGrid>
      </SimpleGrid> */}
      {/* <SimpleGrid columns={{ base: 1, md: 1, xl: 2 }} gap="20px" mb="20px">
        <ComplexTable tableData={tableDataComplex} />
        <SimpleGrid columns={{ base: 1, md: 2, xl: 2 }} gap="20px">
          <Tasks />
          <MiniCalendar h="100%" minW="100%" selectRange={false} /> 
        </SimpleGrid>
      </SimpleGrid> */}
        <VSeparator
        mx="20px"
        bg={paleGray}
        display={{ base: 'none', xl: 'flex' }}
      />
      <YourCard
        maxW={{ base: '100%', xl: '400px' }}
        maxH={{ base: '100%', xl: '1170px', '2xl': '100%' }}
      />
    </Box>
  );
}
