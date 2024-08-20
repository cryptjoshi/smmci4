// Chakra imports
import { Box, Button, Flex, Icon, Text, useColorModeValue } from '@chakra-ui/react';
// Custom components
import Card from 'components/card/Card';
import LineChart from 'components/charts/LineChart';
import dynamic from 'next/dynamic';
//import router from 'next/router';
import { useEffect, useState } from 'react';
import { IoCheckmarkCircle } from 'react-icons/io5';
import { MdBarChart, MdOutlineCalendarToday } from 'react-icons/md';
// Assets
import { RiArrowUpSFill } from 'react-icons/ri';
import { lineChartDataTotalSpent, lineChartOptionsTotalSpent } from 'variables/charts';

import { useRouter } from 'next/navigation';

export default function TotalSpent(props: { [x: string]: any }) {
	const { ...rest } = props;

	// Chakra Color Mode

	const textColor = useColorModeValue('secondaryGray.900', 'white');
	const textColorSecondary = useColorModeValue('secondaryGray.600', 'white');
	const boxBg = useColorModeValue('secondaryGray.300', 'whiteAlpha.100');
	const iconColor = useColorModeValue('brand.500', 'white');
	const bgButton = useColorModeValue('secondaryGray.300', 'whiteAlpha.100');
	const bgHover = useColorModeValue({ bg: 'secondaryGray.400' }, { bg: 'whiteAlpha.50' });
	const bgFocus = useColorModeValue({ bg: 'secondaryGray.300' }, { bg: 'whiteAlpha.100' });

	const router = useRouter()

	const [sumMonth, setMonth] = useState([]);
	const [sumTotal, setTotal] = useState("");

	useEffect(() => {
		let isLoaded = false;

		const checkData = async () => {
			const token = localStorage.getItem('token');
		   
			if(!token){
			  router.replace('/auth/sign-in')
		  } else 
		  {
			
			   // console.log(new Date().format('yyyy-MM-dd'))
				const today = new Date().toJSON().slice(0, 10);
				//const raw = JSON.stringify({"startdate":today,"stopdate":today});
				let data;
			 
			let res = await fetch('https://report.tsxbet.net/reports/sum/monthly', { method: 'POST',
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
			   setMonth(data.data)
			  // const sumtotal = data.data.data.reduce((accumulator:any, current:any) => accumulator + current)
		 
			   setTotal(data.data[0].sum.toFixed(2).toString())
			   lineChartOptionsTotalSpent.xaxis.categories = data.data.daysArray;
			//}
			 // setAuthenticated(true)
		  } else {
			router.replace('/auth/sign-in')
		  } 
			  
			}
		}

		 const timeout = setTimeout(() => {
		 	//setMounted(true);
		 	isLoaded = true;
		}, 3000);
		checkData()
		return () => {
			//isLoaded = true;
		//	checkData();
		 	clearTimeout(timeout);
		 };


		
	}, []);

	return (
		<Card justifyContent='center' alignItems='center' flexDirection='column' w='100%' mb='0px' {...rest}>
			<Flex justify='space-between' ps='0px' pe='20px' pt='5px' w='100%'>
				<Flex align='center' w='100%'>
					<Button bg={boxBg} fontSize='sm' fontWeight='500' color={textColorSecondary} borderRadius='7px'>
						<Icon as={MdOutlineCalendarToday} color={textColorSecondary} me='4px' />
						This month
					</Button>
					<Button
						ms='auto'
						alignItems='center'
						justifyContent='center'
						bg={bgButton}
						_hover={bgHover}
						_focus={bgFocus}
						_active={bgFocus}
						w='37px'
						h='37px'
						lineHeight='100%'
						borderRadius='10px'
						{...rest}>
						<Icon as={MdBarChart} color={iconColor} w='24px' h='24px' />
					</Button>
				</Flex>
			</Flex>
			<Flex w='100%' flexDirection={{ base: 'column', lg: 'row' }}>
				<Flex flexDirection='column' me='20px' mt='28px'>
					<Text color={textColor} fontSize='34px' textAlign='start' fontWeight='700' lineHeight='100%'>
						{sumTotal}
					</Text>
					<Flex align='center' mb='20px'>
						<Text color='secondaryGray.600' fontSize='sm' fontWeight='500' mt='4px' me='12px'>
							Total Topup
						</Text>
						<Flex align='center'>
							<Icon as={RiArrowUpSFill} color='green.500' me='2px' mt='2px' />
							<Text color='green.500' fontSize='sm' fontWeight='700' lineHeight='100%'>
								+2.45%
							</Text>
						</Flex>
					</Flex>

					<Flex align='center'>
						<Icon as={IoCheckmarkCircle} color='green.500' me='4px' />
						<Text color='green.500' fontSize='md' fontWeight='700'>
							On track
						</Text>
					</Flex>
				</Flex>
				<Box minH='260px' minW='75%' mt='auto'>
					<LineChart chartData={sumMonth} chartOptions={lineChartOptionsTotalSpent} />
				</Box>
			</Flex>
		</Card>
	);
}
