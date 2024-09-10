'use client';
import React, { PropsWithChildren, useState,useEffect } from 'react';

// Chakra imports
import {
  Flex,
  Box,
  Button,
  IconButton,
  Icon,
  Text,
  Tabs,
  TabList,
  TabPanels,
  Tab,
  TabPanel,
  useColorModeValue,
  ComponentWithAs,
  ChakraProps,
} from '@chakra-ui/react';

// Custom components
import Card from 'components/card/Card';
import Mastercard from 'components/card/Mastercard';
import Transaction from 'components/dataDisplay/Transaction';

// Assets
import {
  MdOutlineShoppingBasket,
  MdAddCircle,
  MdOutlineDirectionsBus,
  MdOutlineSubscriptions,
  MdLocalBar,
  MdOutlineWeekend,
  MdCached,
  MdAdd,
  MdAttachMoney,
  MdMoreHoriz,
} from 'react-icons/md';
import { RiNetflixFill } from 'react-icons/ri';
import { useRouter } from 'next/navigation';
import { convertDate } from 'utils';
import { getToken } from 'app/actions/userInfof';

interface YourCardProps extends PropsWithChildren, ChakraProps {}

function strToDate(dtStr:string) {
  if (!dtStr) return null
  let dateParts:any = dtStr.split("/");
  let timeParts = dateParts[2].split(" ")[1].split(":");
  dateParts[2] = dateParts[2].split(" ")[0];
  // month is 0-based, that's why we need dataParts[1] - 1
  return new Date(Date.UTC(+dateParts[2], dateParts[1] - 1, +dateParts[0], timeParts[0], timeParts[1], timeParts[2]));
} 

export default function YourCard(props: YourCardProps) {
  const { ...rest } = props;

  const [tabState, setTabState] = useState('card1');

  // Chakra Color Mode
  const iconColor = useColorModeValue('brand.500', 'white');
  const greenIcon = useColorModeValue('green.500', 'white');
  const redIcon = useColorModeValue('red.500', 'white');
  const blueIcon = useColorModeValue('blue.500', 'white');
  const yellowIcon = useColorModeValue('yellow.500', 'white');
  const bgIconButton = useColorModeValue('white', 'whiteAlpha.100');
  const bgIconHover = useColorModeValue(
    { bg: 'gray.100' },
    { bg: 'whiteAlpha.50' },
  );
  const bgIconFocus = useColorModeValue(
    { bg: 'grey.100' },
    { bg: 'whiteAlpha.100' },
  );
  const bgButton = useColorModeValue('secondaryGray.300', 'whiteAlpha.100');
  const bgHover = useColorModeValue(
    { bg: 'secondaryGray.400' },
    { bg: 'whiteAlpha.50' },
  );
  const bgFocus = useColorModeValue(
    { bg: 'secondaryGray.400' },
    { bg: 'whiteAlpha.100' },
  );
  const boxBg = useColorModeValue('secondaryGray.300', 'whiteAlpha.100');
  const shadow = useColorModeValue(
    '18px 17px 40px 4px rgba(112, 144, 176, 0.1)',
    'unset',
  );
  const textColor = useColorModeValue('secondaryGray.900', 'white');


  const router = useRouter()
  const [transfers,setTransfers] = useState<any>({})
  const [loading, setLoading] = useState(true);
	useEffect(() => {
		

		const checkData = async () => {
			const token =  getToken() //localStorage.getItem('token');
		   
			if(!token){
			  router.replace('/auth/sign-in')
		  } else 
		  {
			
			   // console.log(new Date().format('yyyy-MM-dd'))
				const today = new Date().toJSON().slice(0, 10);
				//const raw = JSON.stringify({"startdate":today,"stopdate":today});
				let data;
			 
			let res = await fetch('https://report.tsxbet.net/reports/count/lasttransfer', { method: 'POST',
			  headers: {
				'Accept': 'application/json',
				'Content-Type': 'application/json',
				'Authorization': 'Bearer ' +  token
			  },
		 // body: raw
		  });
		  data = await res.json();
   
		  if(data.status){
			 
			//if(!isLoaded){
			   setTransfers(data.data)
         setLoading(false)
			  // const sumtotal = data.data.data.reduce((accumulator:any, current:any) => accumulator + current)
		 
			  // setTotal(data.data[0].sum.toFixed(2).toString())
        // lineChartOptionsOverallRevenue.xaxis.categories = data.data.dayArray;
			 //  lineChartDataOverallRevenue.xaxis.categories = data.data.daysArray;
			//}
			 // setAuthenticated(true)
		  } else {
		//	router.replace('/auth/sign-in')
		  } 
			  
			}
		}

		 const timeout = setTimeout(() => {
		 	//setMounted(true);
		 //	isLoaded = true;
		}, 3000);

 
		checkData()
  
		return () => {
			//isLoaded = true;
		//	checkData();
		 	clearTimeout(timeout);
		 };


		
	}, []);
  if (loading) {
    return <p>Loading...</p>; // ข้อความระหว่างรอข้อมูล
}

if (!transfers) {
    return <p>No data available</p>; // ข้อความเมื่อไม่มีข้อมูล
}


  return (
    <Card {...rest}>
      
      <Text fontSize="xl" color={textColor} fontWeight="700" mb="34px">
        {"เคลื่อนไหวล่าสุด"}
      </Text>
      {
        transfers.map((item:any,index:number) => (  
        
        <Transaction
        key={index}
        mb="26px"
       
        name={item.fullname}
        date={convertDate(item.createdAt)}
        sum={parseFloat(item.amount).toLocaleString("en-US",{minimumFractionDigits:2, maximumFractionDigits:2})}
        icon={
          <Icon
            as={MdOutlineDirectionsBus}
            color={iconColor}
            key={index}
            w="20px"
            h="18px"
          />
        }
      />
        )
      )}
      
      
    </Card>
  );
}
