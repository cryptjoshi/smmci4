/* eslint-disable */

import {
  Box,
  Badge,  
  Flex,
  Button,
  Icon,
  Table,
  Tbody,
  Td,
  Text,
  Th,
  Thead,
  Tr,
  useColorModeValue,
} from '@chakra-ui/react';
import {
  createColumnHelper,
  flexRender,
  getCoreRowModel,
  getSortedRowModel,
  SortingState,
  useReactTable,
} from '@tanstack/react-table';
import { MdOutlineCalendarToday } from 'react-icons/md';
// Custom components
import Card from 'components/card/Card';
import * as React from 'react';
import { useRouter } from 'next/navigation';
import { useEffect, useState } from 'react';
import Transfer from '../../../dataDisplay/Transfer';
import { convertDate } from 'utils';
import { getToken } from 'app/actions/userInfof';
// Assets

type RowObj = {
  id:string;
  fullname: string;
  bankname: string;
  banknumber:string;
  createdAt: string;
  status: string;
};

const columnHelper = createColumnHelper<RowObj>();

// const columns = columnsDataCheck;
export default function NewCustomer(props: { tableData: any }) {
  const { tableData } = props;
  const [sorting, setSorting] = React.useState<SortingState>([]);
  const textColor = useColorModeValue('secondaryGray.900', 'white');
  const textColorSecondary = useColorModeValue('secondaryGray.600', 'white');
  const borderColor = useColorModeValue('gray.200', 'whiteAlpha.100');
  const boxBg = useColorModeValue('secondaryGray.300', 'whiteAlpha.100');

  const router = useRouter()
  const [transfers,setTransfers] = useState<RowObj[]>([])
  const [loading, setLoading] = useState(true);
  const [data, setData] = React.useState(() => [...transfers]);

	useEffect(() => {
		let isLoaded = false;

		const checkData = async () => {
			const token = getToken()// localStorage.getItem('token');
		   
			if(!token){
			  router.replace('/auth/sign-in')
		  } else 
		  {
			
			   // console.log(new Date().format('yyyy-MM-dd'))
				const today = new Date().toJSON().slice(0, 10);
				//const raw = JSON.stringify({"startdate":today,"stopdate":today});
				let data;
			 
			let res = await fetch('https://report.tsxbet.net/reports/count/lastnewcustomer', { method: 'POST',
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
			 
		//	if(!isLoaded){
			   setTransfers(data.data)
         setData(data.data)
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
		 	isLoaded = true;
		}, 3000);

 
		checkData()
  
		return () => {
			//isLoaded = true;
		//	checkData();
		 	clearTimeout(timeout);
		 };


		
	}, []);

  
  const columns = [
    columnHelper.accessor('fullname', {
      id: 'fullname',
      header: () => (
        <Text
          justifyContent="space-between"
          align="center"
          fontSize={{ sm: '10px', lg: '12px' }}
          color="gray.400"
        >
          {"ชื่อ"}
        </Text>
      ),
      cell: (info: any) => (
        <Text color={textColor} fontSize="sm" fontWeight="600">
          {info.getValue()}
        </Text>
      ),
    }),
    columnHelper.accessor('banknumber', {
      id: 'banknumber',
      header: () => (
        <Text
          justifyContent="space-between"
          align="center"
          fontSize={{ sm: '10px', lg: '12px' }}
          color="gray.400"
        >
         {"เลขบัญชี"}
        </Text>
      ),
      cell: (info:any) => (
        <Text color={textColor} fontSize="sm" fontWeight="600">
          {info.getValue()}
        </Text>
      ),
    }),
    columnHelper.accessor('bankname', {
      id: 'bankname',
      header: () => (
        <Text
          justifyContent="space-between"
          align="center"
          fontSize={{ sm: '10px', lg: '12px' }}
          color="gray.400"
        >
         {"ธนาคาร"}
        </Text>
      ),
      cell: (info:any) => (
        <Text color={textColor} fontSize="sm" fontWeight="600">
          {info.getValue()}
        </Text>
      ),
    }),
    columnHelper.accessor('createdAt', {
      id: 'createdAt',
      header: () => (
        <Text
          justifyContent="space-between"
          align="center"
          fontSize={{ sm: '10px', lg: '12px' }}
          color="gray.400"
        >
         {" เวลา"}
        </Text>
      ),
      cell: (info:any) => (
        <Text color={textColor} fontSize="sm" fontWeight="700">
          {convertDate(info.getValue())}
        </Text>
      ),
    }),
    columnHelper.accessor('status', {
      id: 'status',
      header: () => (
        <Text
          justifyContent="space-between"
          align="center"
          fontSize={{ sm: '10px', lg: '12px' }}
          color="gray.400"
        >
          {"สถานะ"}
        </Text>
      ),
      cell: (info:any) => (
        // <Text color={ info.getValue()=="1"?'green.500' : 'red.500'} fontSize="sm" fontWeight="700">
        //   {info.getValue()=="1"?"ทำงาน":"ไม่ทำงาน"}
        // </Text>
        <Badge
        colorScheme={
          info.getValue()=="1" ? 'green' : 'red'
        }
        color={ info.getValue()=="1"?'green.500' : 'red.500'}
        fontSize="md"
        fontWeight="500"
    >
     {info.getValue()=="1"?"ทำงาน":"ไม่ทำงาน"}
    </Badge>
      ),
    }),
    // columnHelper.accessor('transactionamount', {
    //   id: 'transactionamount',
    //   header: () => (
    //     <Text
    //       justifyContent="space-between"
    //       align="center"
    //       fontSize={{ sm: '10px', lg: '12px' }}
    //       color="gray.400"
    //     >
    //       {"จำนวนเงิน"}
    //     </Text>
    //   ),
    //   cell: (info:any) => (
    //     <Text
    //       color={info.getValue() >0 ? 'green.500' : 'red.500'}
    //       fontSize="sm"
    //       fontWeight="600"
    //     >
    //       {info.getValue()}
    //     </Text>
    //   ),
    // }),
  ];
  
  //const [data, setData] = React.useState(() => [...transfers]);

  const table = useReactTable({
    data,
    columns,
    state: {
      sorting,
    },
    onSortingChange: setSorting,
    getCoreRowModel: getCoreRowModel(),
    getSortedRowModel: getSortedRowModel(),
    debugTable: true,
  });

  //let defaultData = tableData;
  if(loading){
    return <p>Loading...</p>
  }
   
 if(!transfers){
   return <p>No data available</p>
 }

  return (
    <Card
      flexDirection="column"
      w="100%"
      px="0px"
      overflowX={{ sm: 'scroll', lg: 'hidden' }}
    >
      <Flex align="center" justify="space-between" w="100%" px="10px" mb="20px">
        <Text
          color={textColor}
          fontSize="lg"
          fontWeight="700"
          lineHeight="100%"
        >
          {"ลูกค้าสมัครล่าสุด"}
        </Text>
        {/* <Button
          bg={boxBg}
          fontSize="sm"
          fontWeight="500"
          color={textColorSecondary}
          borderRadius="7px"
        >
          <Icon
            as={MdOutlineCalendarToday}
            color={textColorSecondary}
            me="4px"
          />
          This month
        </Button> */}
      </Flex>
      <Box>
        <Table variant="simple" color="gray.500" mt="12px">
          <Thead>
            {table.getHeaderGroups().map((headerGroup) => (
              <Tr key={headerGroup.id}>
                {headerGroup.headers.map((header) => {
                  return (
                    <Th
                      key={header.id}
                      colSpan={header.colSpan}
                      pe="10px"
                      borderColor={borderColor}
                      cursor="pointer"
                      onClick={header.column.getToggleSortingHandler()}
                    >
                      <Flex
                        justifyContent="space-between"
                        align="center"
                        fontSize={{ sm: '10px', lg: '12px' }}
                        color="gray.400"
                      >
                        {flexRender(
                          header.column.columnDef.header,
                          header.getContext(),
                        )}
                        {{
                          asc: '',
                          desc: '',
                        }[header.column.getIsSorted() as string] ?? null}
                      </Flex>
                    </Th>
                  );
                })}
              </Tr>
            ))}
          </Thead>
          <Tbody>
            {table
              .getRowModel()
              .rows.slice(0, 11)
              .map((row) => {
                return (
                  <Tr key={row.id}>
                    {row.getVisibleCells().map((cell) => {
                      return (
                        <Td
                          key={cell.id}
                          fontSize={{ sm: '14px' }}
                          minW={{ sm: '150px', md: '200px', lg: 'auto' }}
                          borderColor="transparent"
                        >
                          {flexRender(
                            cell.column.columnDef.cell,
                            cell.getContext(),
                          )}
                        </Td>
                      );
                    })}
                  </Tr>
                );
              })}
          </Tbody>
        </Table>
      </Box>
    </Card>
  );
}
