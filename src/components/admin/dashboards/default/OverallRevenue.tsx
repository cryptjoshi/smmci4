// Chakra imports
import {
  Flex,
  Box,
  Icon,
  Select,
  Text,
  useColorModeValue,
} from '@chakra-ui/react';
import LineChart from 'components/charts/LineChart';

// Custom components
import Card from 'components/card/Card';
import {
  lineChartDataOverallRevenue,
  lineChartOptionsOverallRevenue,
} from 'variables/charts';

// Assets
import { RiArrowUpSFill } from 'react-icons/ri';
import { useEffect, useState } from 'react';
import { useRouter } from 'next/navigation';

export default function OverallRevenue(props: { [x: string]: any }) {
  const { ...rest } = props;
  //eslint-disable-next-line

  const newOptions = {
    ...lineChartOptionsOverallRevenue,
    // colors: ['var(--chakra-colors-brand-500)', '#39B8FF'],
  };

  // Chakra Color Mode
  const textColor = useColorModeValue('secondaryGray.900', 'white');
	const router = useRouter()

	const [sumMonth, setMonth] = useState([]);
	const [sumTotal, setTotal] = useState("");
  const [loading, setLoading] = useState(true);
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
			 
			let res = await fetch('http://128.199.92.45:3003/reports/count/sumonthly', { method: 'POST',
			  headers: {
				'Accept': 'application/json',
				'Content-Type': 'application/json',
				'Authorization': 'Bearer ' +  token
			  },
		 // body: raw
		  });
		  data = await res.json();
     // console.log(data)

		  if(data.status){
			 
			//if(!isLoaded){
			   setMonth(data.data)
			  // const sumtotal = data.data.data.reduce((accumulator:any, current:any) => accumulator + current)
		 //console.log(data.data)
			   setTotal(data.data[0].sum.toFixed(2).toString())
         //console.log(data.data.daysArray)
         setLoading(false)
         var now = new Date();
         const daysofmonth = new Date(now.getFullYear(), now.getMonth()+1, 0).getDate();
         
         let daysArray = Array.from({ length: daysofmonth }, (_, i) => (i + 1).toString());
         lineChartOptionsOverallRevenue.xaxis.categories=daysArray
         console.log(lineChartOptionsOverallRevenue)
         //Object.assign(lineChartOptionsOverallRevenue.xaxis.categories, daysArray);
          //= daysArray //data.data.daysArray;
			 //  lineChartDataOverallRevenue.xaxis.categories = data.data.daysArray;
		//	}
			 // setAuthenticated(true)
		  } else {
		//	router.replace('/auth/sign-in')
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
  if (loading) {
    return <p>Loading...</p>; // ข้อความระหว่างรอข้อมูล
}

if (!sumMonth) {
    return <p>No data available</p>; // ข้อความเมื่อไม่มีข้อมูล
}
  return (
    <Card
      justifyContent="center"
      alignItems="center"
      flexDirection="column"
      w="100%"
      mb={{ base: '20px', lg: '0px' }}
      {...rest}
    >
      <Flex justify="space-between" px="20px" pt="5px" w="100%">
        <Flex align="center" w="100%">
          <Flex flexDirection="column" me="20px">
            <Text
              color={textColor}
              fontSize="34px"
              fontWeight="700"
              lineHeight="100%"
            >
             {sumTotal}
            </Text>
            <Text
              color="secondaryGray.600"
              fontSize="md"
              fontWeight="500"
              mt="4px"
            >
              {"ยอดลูกค้าลงทะเบียน/ยอดลูกค้า Active"}
            </Text>
          </Flex>
          {/* <Flex align="center">
            <Icon as={RiArrowUpSFill} color="green.500" me="2px" />
            <Text color="green.500" fontSize="sm" fontWeight="700">
              +2.45%
            </Text>
          </Flex> */}
        </Flex>
        <Select
          fontSize="sm"
          variant="subtle"
          defaultValue="monthly"
          width="unset"
          fontWeight="700"
        >
         <option value="daily">ประจำวัน</option>
          <option value="monthly">รายเดือน</option>
          <option value="yearly">รายปี</option>
        </Select>
      </Flex>
      <Box minH="260px" mt="auto" w="100%">
        <LineChart
          chartData={sumMonth}
          chartOptions={lineChartOptionsOverallRevenue}
        />
      </Box>
    </Card>
  );
}
