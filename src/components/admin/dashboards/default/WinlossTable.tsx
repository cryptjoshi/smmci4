/* eslint-disable */

import {
  Badge,
  Box,
  Flex,
  Grid,
  Button,
  Icon,
  Link,
  Table,
  Tbody,
  Td,
  Text,
  Th,
  Thead,
  Tr,
  useColorModeValue,
  Stack
   
} from '@chakra-ui/react';

 
 
import {
  PaginationState,
  createColumnHelper,
  useReactTable,
  ColumnFiltersState,
  getCoreRowModel,
  getFilteredRowModel,
  getFacetedRowModel,
  getFacetedUniqueValues,
  getFacetedMinMaxValues,
  getPaginationRowModel,
  getSortedRowModel,
  SortingState,
  flexRender,
} from '@tanstack/react-table';

import { MdOutlineCalendarToday,MdChevronLeft,MdChevronRight } from 'react-icons/md';
import useSWR from "swr";
// Assets
import { SearchBar } from 'components/navbar/searchBar/SearchBar';
 
import MiniCalendar from 'components/calendar/MiniCalendar';


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
  createdAt:string;
  MemberID:string;
  MemberName:string;
  GameID:string;
  GameRoundID:string;
  BeforeBalance:string;
  BetAmount:number;
  PayoutAmount:number;
  PayoutDetail:string;
  AfterBalance:number;
  winloss:number;
  loss:number;
  turnover:number;
  month:number;
};
//const startdate =  new Date(new Date().setDate(new Date().getDate() - 7)).toJSON().slice(0, 10);
 // const stopdate =  new Date(new Date().setDate(new Date().getDate() + 7)).toJSON().slice(0, 10);
  //const token = localStorage.getItem('token');
  //const raw = JSON.stringify({"startdate":startdate,"stopdate":stopdate,"prefix":"all","statement_type":"all","status":"all"});
	
const columnHelper = createColumnHelper<RowObj>();
const fetcher = (url:string) => fetch(url,{ method: 'POST',
  headers: {
  'Accept': 'application/json',
  'Content-Type': 'application/json',
  'Authorization': 'Bearer ' +  getToken()// localStorage.getItem('token')
  },
 body: JSON.stringify({"startdate":new Date(new Date().setDate(new Date().getDate() - 7)).toJSON().slice(0, 10),"stopdate":new Date(new Date().setDate(new Date().getDate() + 7)).toJSON().slice(0, 10),"prefix":"all","statement_type":"all","status":"all"})
}).then((res) => res.json());
// const columns = columnsDataCheck;
export default function WinlossTable(props: { tableData: any }) {
  const { tableData } = props;
  const [sorting, setSorting] = useState<SortingState>([]);
  const textColor = useColorModeValue('secondaryGray.900', 'white');
  const textColorSecondary = useColorModeValue('secondaryGray.600', 'white');
  const borderColor = useColorModeValue('gray.200', 'whiteAlpha.100');
  const boxBg = useColorModeValue('secondaryGray.300', 'whiteAlpha.100');

  const brandColor = useColorModeValue('brand.500', 'brand.400');
  const router = useRouter()
  //const [transfers,setTransfers] = useState<RowObj[]>([])
   
  //const [data, setData] =  useState(() => [...transfers]);
  const [columnFilters, setColumnFilters] = useState<ColumnFiltersState>([]);
 
  const [globalFilter, setGlobalFilter] =  useState('');
  
  		
  const { data:transfers, error, isLoading } = useSWR(
    "https://report.tsxbet.net/reports/sumwinloss",
    fetcher
  );
  //const [data, setData] =  useState([...transfers]);
  //const [transfers, setTransfer] = useState<RowObj[]>(() => [...data]);
  const [{ pageIndex, pageSize }, setPagination] =
    useState<PaginationState>({
        pageIndex: 0,
        pageSize: 20,
    });

const pagination = React.useMemo(
  () => ({
      pageIndex,
      pageSize,
  }),
  [pageIndex, pageSize]
);
 
const createPages = (count: number) => {
  let arrPageCount = [];

  for (let i = 1; i <= count; i++) {
      arrPageCount.push(i);
  }

  return arrPageCount;
};

	// useEffect(() => {
	// 	let isLoaded = false;

	// 	const checkData = async () => {
	// 		const token = localStorage.getItem('token');
		   
	// 		if(!token){
	// 		  router.replace('/auth/sign-in')
	// 	  } else 
	// 	  {
			
	// 		   // console.log(new Date().format('yyyy-MM-dd'))
	// 			const today = new Date().toJSON().slice(0, 10);
      
        
  //       const startdate =  new Date(new Date().setDate(new Date().getDate() - 7)).toJSON().slice(0, 10);
				
  //       const stopdate =  new Date(new Date().setDate(new Date().getDate() + 7)).toJSON().slice(0, 10);
	// 			const raw = JSON.stringify({"startdate":startdate,"stopdate":stopdate,"prefix":"all","statement_type":"all","status":"all"});
				
			 
	// 		let res = await fetch('https://report.tsxbet.net/reports/winloss', { method: 'POST',
	// 		  headers: {
	// 			'Accept': 'application/json',
	// 			'Content-Type': 'application/json',
	// 			'Authorization': 'Bearer ' +  token
	// 		  },
	// 	  body: raw
	// 	  });
	// 	  let xdata = await res.json();
     
	// 	  if(xdata.status){
			 
	// 	//	if(!isLoaded){
	// 		   setTransfers(xdata.data)
  //        setData(xdata.data)
  //        setLoading(false)
	// 		  // const sumtotal = data.data.data.reduce((accumulator:any, current:any) => accumulator + current)
		 
	// 		  // setTotal(data.data[0].sum.toFixed(2).toString())
  //       // lineChartOptionsOverallRevenue.xaxis.categories = data.data.dayArray;
	// 		 //  lineChartDataOverallRevenue.xaxis.categories = data.data.daysArray;
	// 		//}
	// 		 // setAuthenticated(true)
	// 	  } else {
	// 	//	router.replace('/auth/sign-in')
	// 	  } 
			  
	// 		}
	// 	}

	// 	 const timeout = setTimeout(() => {
	// 	 	//setMounted(true);
	// 	 	isLoaded = true;
	// 	}, 3000);

 
	// 	checkData()
  
	// 	return () => {
	// 		//isLoaded = true;
	// 	//	checkData();
	// 	 	clearTimeout(timeout);
	// 	 };


		
	// }, []);

  
  const columns = [
   
    // columnHelper.accessor('MemberID', {
    //   id: 'MemberID',
    //   header: () => (
    //     <Text
    //       justifyContent="space-between"
    //       align="center"
    //       fontSize={{ sm: '10px', lg: '12px' }}
    //       color="gray.400"
    //     >
    //       {"รหัสสมาชิก"}
    //     </Text>
    //   ),
    //   cell: (info: any) => (
    //     <Button >
    //     <Text color='blue.500' fontSize="sm" fontWeight="600"  >
    //     {info.getValue()}
    //     </Text>
    //     </Button>
     
  
    //   ),
    // }),
    columnHelper.accessor('MemberName', {
      id: 'MemberName',
      header: () => (
        <Text
          justifyContent="space-between"
          align="left"
          fontSize={{ sm: '10px', lg: '12px' }}
          color="gray.400"
        >
         {"ชื่อสมาชิก"}
        </Text>
      ),
      cell: (info:any) => (
      <Link href={`/admin/winloss/detail/${info.getValue()}`}>
      <Button >
        <Text color={textColor} fontSize="sm" fontWeight="600">
          {info.getValue()}
        </Text>
        </Button>
        </Link>
      ),
    }),
    // columnHelper.accessor('createdAt', {
    //   id: 'createdAt',
    //   header: () => (
    //     <Text
    //       justifyContent="space-between"
    //       align="center"
    //       fontSize={{ sm: '10px', lg: '12px' }}
    //       color="gray.400"
    //     >
    //      {"วันที่"}
    //     </Text>
    //   ),
    //   cell: (info:any) => (
    //     <Text color={textColor} fontSize="sm" fontWeight="700">
    //       {convertDate(info.getValue())}
    //     </Text>
    //   ),
    // }),
    // columnHelper.accessor('GameID', {
    //   id: 'GameID',
    //   header: () => (
    //     <Text
    //       justifyContent="space-between"
    //       align="left"
    //       fontSize={{ sm: '10px', lg: '12px' }}
    //       color="gray.400"
    //     >
    //      {"รหัสเกมส์"}
    //     </Text>
    //   ),
    //   cell: (info:any) => (
    //     <Text color={textColor} fontSize="sm" fontWeight="600">
    //       {info.getValue()}
    //     </Text>
    //   ),
    // }),
    // columnHelper.accessor('GameRoundID', {
    //   id: 'GameRoundID',
    //   header: () => (
    //     <Text
    //       justifyContent="space-between"
    //       align="left"
    //       fontSize={{ sm: '10px', lg: '12px' }}
    //       color="gray.400"
    //     >
    //      {"รอบเกมส์"}
    //     </Text>
    //   ),
    //   cell: (info:any) => (
    //     <Text color={textColor} fontSize="sm" fontWeight="600">
    //       {info.getValue()}
    //     </Text>
    //   ),
    // }),
    columnHelper.accessor('BetAmount', {
      id: 'BetAmount',
      header: () => (
        <Text
          justifyContent="space-between"
          align="center"
          fontSize={{ sm: '10px', lg: '12px' }}
          color="gray.400"
        >
         {"ยอดแทง"}
        </Text>
      ),
      cell: (info:any) => (
        <Text color={ 'blue.500'} fontSize="sm" fontWeight="600">
          {info.getValue()?info.getValue().toFixed(2):0}
        </Text>
      ),
    }),
    columnHelper.accessor('PayoutAmount', {
      id: 'PayoutAmount',
      header: () => (
        <Text
          justifyContent="space-between"
          align="center"
          fontSize={{ sm: '10px', lg: '12px' }}
          color="gray.400"
        >
         {"ยอดจ่าย"}
        </Text>
      ),
      cell: (info:any) => (
        <Text color={
          info.getValue() >0
        ? 'green.500'
        : 'gray.200'
            
        }  
          fontSize="sm" fontWeight="600">
          {info.getValue()?info.getValue().toFixed(2):0}
        </Text>
      ),
    }),
    columnHelper.accessor('WINLOSS', {
      id: 'WINLOSS',
      header: () => (
        <Text
          justifyContent="space-between"
          align="center"
          fontSize={{ sm: '10px', lg: '12px' }}
          color="gray.400"
        >
         {"ได้/เสีย"}
        </Text>
      ),
      cell: (info:any) => (
        <Text color={
          info.getValue() >0
              ? 'green.500'
              : 'red.500'
      } fontSize="sm" fontWeight="600">
          {info.getValue()?info.getValue().toFixed(2):0}
        </Text>
      ),
    }),
   
    // columnHelper.accessor('LOSS', {
    //   id: 'LOSS',
    //   header: () => (
    //     <Text
    //       justifyContent="space-between"
    //       align="center"
    //       fontSize={{ sm: '10px', lg: '12px' }}
    //       color="gray.400"
    //     >
    //      {"เสีย"}
    //     </Text>
    //   ),
    //   cell: (info:any) => (
    //     <Text 
    //     color={
    //       info.getValue() <0
    //           ? 'red.500'
    //           : 'gray.200'
    //     }  fontSize="sm" fontWeight="600">
    //        {info.getValue().toFixed(2)}
    //     </Text>
    //   ),
    // }),
    columnHelper.accessor('TURNOVER', {
      id: 'TURNOVER',
      header: () => (
        <Text
          justifyContent="space-between"
          align="center"
          fontSize={{ sm: '10px', lg: '12px' }}
          color="gray.400"
        >
         {"เทิร์น"}
        </Text>
      ),
      cell: (info:any) => (
        <Text color={info.getValue()>0?'blue.300':'red.300'} fontSize="sm" fontWeight="600">
          {info.getValue()?info.getValue().toFixed(2):0}
        </Text>
      ),
    }),
 
  ];
  
 

  const table = useReactTable({
    ...transfers,
    columns,
    state: {
      sorting,
      columnFilters,
      globalFilter,
      pagination,
    },
    onPaginationChange: setPagination,
    onColumnFiltersChange: setColumnFilters,
    onGlobalFilterChange: setGlobalFilter,
    getCoreRowModel: getCoreRowModel(),
    getFilteredRowModel: getFilteredRowModel(),
    getSortedRowModel: getSortedRowModel(),
    getPaginationRowModel: getPaginationRowModel(),
    getFacetedRowModel: getFacetedRowModel(),
    getFacetedUniqueValues: getFacetedUniqueValues(),
    getFacetedMinMaxValues: getFacetedMinMaxValues(),
    debugTable: true,
    debugHeaders: true,
    debugColumns: false,
    onSortingChange: setSorting
  });

  //let defaultData = tableData;
  if (error) return <>"An error has occurred."</>;
  if (isLoading) return <>"Loading..."</>;
  if(!isLoading){
   // set(data)
  }
  return (
    <>
     {/* <Card
              alignItems="center"
              flexDirection="column"
              gridArea="1 / 2 / 2 / 3"
              w="100%"
          >
              <Grid
                  templateColumns={{ md: 'repeat(2, 1fr)', lg: '1fr' }}
                  w={'100%'}
                  justifyContent={'center'}
              >
                  <MiniCalendar
                      gridArea={{ md: '1 / 1 / 2 / 2;', lg: '1 / 1 / 2 / 2' }}
                      selectRange={false}
                      mb="20px"
                  />
                
                 
              </Grid>
          </Card> */}
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
          ml="5"
        >
          {"รายงานยอดได้เสีย"}
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
      <Flex
                align={{ sm: 'flex-start', lg: 'flex-start' }}
                justify={{ sm: 'flex-start', lg: 'flex-start' }}
                w="100%"
                px="22px"
                mb="36px"
            >
                <DebouncedInput
                    value={globalFilter ?? ''}
                    onChange={(value) => setGlobalFilter(String(value))}
                    className="p-2 font-lg shadow border border-block"
                    placeholder="Search..."
                />
            </Flex>
        <Table variant="simple" color="gray.500" mt="12px">
          <Thead>
            {table.getHeaderGroups().map((headerGroup:any) => (
              <Tr key={headerGroup.id}>
                {headerGroup.headers.map((header:any) => {
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
              .rows.slice(0, pageSize+1)
              .map((row:any) => {
                return (
                  <Tr key={row.id}>
                    {row.getVisibleCells().map((cell:any) => {
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
        <Flex w="100%" justify="space-between" px="20px" pt="10px" pb="5px">
                {/* SET ROW NUMBER */}
                <Text
                    fontSize="sm"
                    color="gray.500"
                    fontWeight="normal"
                    mb={{ sm: '24px', md: '0px' }}
                >
                    Showing {pageSize * pageIndex + 1} to{' '}
                    {pageSize * (pageIndex + 1) <= transfers.data.length
                        ? pageSize * (pageIndex + 1)
                        : transfers.data.length}{' '}
                    of {transfers.data.length} entries
                </Text>
                {/* PAGINATION BUTTONS */}
                <div className="flex items-center gap-2">
                    <Stack
                        direction="row"
                        alignSelf="flex-end"
                        spacing="4px"
                        ms="auto"
                    >
                        <Button
                            variant="no-effects"
                            onClick={() => table.previousPage()}
                            disabled={!table.getCanPreviousPage()}
                            transition="all .5s ease"
                            w="40px"
                            h="40px"
                            borderRadius="50%"
                            bg="transparent"
                            border="1px solid"
                            borderColor={useColorModeValue('gray.200', 'white')}
                            display={
                                pageSize === 50
                                    ? 'none'
                                    : table.getCanPreviousPage()
                                    ? 'flex'
                                    : 'none'
                            }
                            _hover={{
                                bg: 'whiteAlpha.100',
                                opacity: '0.7',
                            }}
                        >
                            <Icon
                                as={MdChevronLeft}
                                w="16px"
                                h="16px"
                                color={textColor}
                            />
                        </Button>
                        {createPages(table.getPageCount()).map(
                            (pageNumber, index) => {
                                return (
                                    <Button
                                        variant="no-effects"
                                        transition="all .5s ease"
                                        onClick={() =>
                                            table.setPageIndex(pageNumber - 1)
                                        }
                                        w="40px"
                                        h="40px"
                                        borderRadius="50%"
                                        bg={
                                            pageNumber === pageIndex + 1
                                                ? brandColor
                                                : 'transparent'
                                        }
                                        border={
                                            pageNumber === pageIndex + 1
                                                ? 'none'
                                                : '1px solid lightgray'
                                        }
                                        _hover={
                                            pageNumber === pageIndex + 1
                                                ? {
                                                      opacity: '0.7',
                                                  }
                                                : {
                                                      bg: 'whiteAlpha.100',
                                                  }
                                        }
                                        key={index}
                                    >
                                        <Text
                                            fontSize="sm"
                                            color={
                                                pageNumber === pageIndex + 1
                                                    ? '#fff'
                                                    : textColor
                                            }
                                        >
                                            {pageNumber}
                                        </Text>
                                    </Button>
                                );
                            }
                        )}
                        <Button
                            variant="no-effects"
                            onClick={() => table.nextPage()}
                            disabled={!table.getCanNextPage()}
                            transition="all .5s ease"
                            w="40px"
                            h="40px"
                            borderRadius="50%"
                            bg="transparent"
                            border="1px solid"
                            borderColor={useColorModeValue('gray.200', 'white')}
                            display={
                                pageSize === 50
                                    ? 'none'
                                    : table.getCanNextPage()
                                    ? 'flex'
                                    : 'none'
                            }
                            _hover={{
                                bg: 'whiteAlpha.100',
                                opacity: '0.7',
                            }}
                        >
                            <Icon
                                as={MdChevronRight}
                                w="16px"
                                h="16px"
                                color={textColor}
                            />
                        </Button>
                    </Stack>
                </div>
            </Flex>
      </Box>
     
    </Card>
   
    </>
  );
}
function DebouncedInput({
  value: initialValue,
  onChange,
  debounce = 500,
  ...props
}: {
  value: string | number;
  onChange: (value: string | number) => void;
  debounce?: number;
} & Omit<React.InputHTMLAttributes<HTMLInputElement>, 'onChange'>) {
  const [value, setValue] = React.useState(initialValue);

  React.useEffect(() => {
      setValue(initialValue);
  }, [initialValue]);

  React.useEffect(() => {
      const timeout = setTimeout(() => {
          onChange(value);
      }, debounce);

      return () => clearTimeout(timeout);
  }, [value]);

  return (
      <SearchBar
          value={value}
          onChange={(e: any) => setValue(e.target.value)}
          h="44px"
          w={{ lg: '390px' }}
          borderRadius="16px"
      />
  );
}
