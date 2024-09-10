/* eslint-disable */
'use client'
import {
  Badge,
  Box,
  Flex,
  Button,
  Icon,
  Image,
  FormLabel,
  Link,
  Table,
  Tbody,
  Td,
  Text,
  Th,
  Thead,
  Tr,
  useColorModeValue,
  Stack,
  VStack,
  HStack,
  Select,
  Stat,
  StatLabel,
  StatNumber,
  StatHelpText,
  StatArrow,
  StatGroup,
  SimpleGrid
   
} from '@chakra-ui/react';

import Swal from 'sweetalert2'
 
import withReactContent from 'sweetalert2-react-content';
import MiniCalendar from 'components/calendar/MiniCalendar'
import MiniStatistics from 'components/card/MiniStatistics'
import IconBox from 'components/icons/IconBox'

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

import {MdChevronLeft,MdChevronRight,MdBarChart,MdAttachMoney,MdAddTask,MdFileCopy,MdContacts } from 'react-icons/md';
import { SearchBar } from 'components/navbar/searchBar/SearchBar';

// Custom components
import Card from 'components/card/Card';

import * as React from 'react';
import { useRouter } from 'next/navigation';
import {  useState } from 'react';

//import { convertDate } from 'utils';

// Assets
import { InputLabel } from 'components/InputLabel';
import { SingleDatepicker } from 'components/Datepicker/DayzedDatepicker';

import { getFirstTransaction, updateFirstTransaction } from 'app/actions/auth';
import Usa from 'img/dashboards/usa.png'

type RowObj = {
  uid: string;
  bankname: string;
  accountno:string;
  createdAt: string;
  statement_type:string;
  transactionamount:number;
  status: string;
};

//const columnHelper = createColumnHelper<RowObj>();
const MySwal = withReactContent(Swal);
const compareDate = (date1:any, date2:any) =>{
  const d1 = new Date(date1);
  const d2 = new Date(date2);

  // เปรียบเทียบวันที่ ถ้า date2 น้อยกว่า date1 ให้แสดง false
  if (d2 < d1) {
    return false;
  }
  return true;
}

const addDays = (dateTime:any, count_days = 0)=>{
  return new Date(new Date(dateTime).setDate(dateTime.getDate() + count_days));
}
export default function FirstTransaction(props: { tableData: any }) {
  
    const { tableData } = props;
  // const [sorting, setSorting] = useState<SortingState>([]);
  // const textColor = useColorModeValue('secondaryGray.900', 'white');
  // const textColorSecondary = useColorModeValue('secondaryGray.600', 'white');
  // const borderColor = useColorModeValue('gray.200', 'whiteAlpha.100');
  const boxBg = useColorModeValue('secondaryGray.300', 'whiteAlpha.100');
  const searchBox = useColorModeValue('gray.200', 'whiteAlpha.100');

  const brandColor = useColorModeValue('brand.500', 'brand.400');
  let secondaryText = useColorModeValue('gray.700', 'white')
  let navbarPosition = 'fixed' as const
  let navbarFilter = 'none'
  let navbarBackdrop = 'blur(20px)'
  let navbarShadow = 'none'
  let navbarBg = useColorModeValue(
    'rgba(244, 247, 254, 0.2)',
    'rgba(11,20,55,0.5)'
  )
  let navbarBorder = 'transparent'
  // const router = useRouter()
   const [transfers,setTransfers] = useState(tableData)
  // const [loading, setLoading] = useState(true);
  const [data, setData] =  useState(tableData);
 // const [columnFilters, setColumnFilters] = useState<ColumnFiltersState>([]);
  const [globalFilter, setGlobalFilter] =  useState('');
  const [{ pageIndex, pageSize }, setPagination] =useState<PaginationState>({pageIndex: 0,pageSize: 6});
  const [endDate, setEndDate] = useState(addDays(new Date(),0));
  const [startDate,setStartDate] = useState(addDays(new Date(),0));

const pagination = React.useMemo(() => ({pageIndex,pageSize}),[pageIndex, pageSize]);
 
const createPages = (count: number) => {
  
  let arrPageCount = [];

  for (let i = 1; i <= count; i++) {
      arrPageCount.push(i);
  }

  return arrPageCount;
};


  const fetchData =  () =>{
        
        //startDate.setDate(startDate.getDate() + 1)
        //endDate.setDate(endDate.getDate() + 1)
        if(compareDate(addDays(startDate,1),addDays(endDate, 1)))
        {
          updateFirstTransaction().then((res)=>console.log(res)).catch(error=>console.error(error))
         // console.log((new Date(startDate + 1*24*60*60*1000),new Date(endDate + 1*24*60*60*1000)))
          getFirstTransaction(addDays(startDate,1).toString(),addDays(endDate, 1).toString()).then((result)=>{
            
          setTransfers(result)
          setData(result)
          }).catch(error => {
          console.error('Error fetching data:', error);
        });
      }  else {
        MySwal.fire({
          title: 'ผืดพลาด',
          text: 'วันที่สิ้นสุด น้อยกว่าวันที่เริ่มต้น !',
          icon: 'error',
          confirmButtonText: 'ตกลง',
          
        });
      }
     
   }
   
  return (
    <>
    {/* <Card
      flexDirection="column"
      w="100%"
      px="0px"
      overflowX={{ sm: 'scroll', lg: 'hidden' }}
      mb="36px"
      bg={navbarBg}
      borderColor={navbarBorder}
      filter={navbarFilter}
      backdropFilter={navbarBackdrop}
    >
        */}
     
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
                 <Flex 
             align={{ sm: 'flex-end', lg: 'flex-end' }}
             justify={{ sm: 'flex-end', lg: 'flex-end' }}
             w="100%"
             
            > 
                   <HStack>
        <VStack alignItems="flex-start">
          <InputLabel>วันที่เริ่มต้น</InputLabel>
          <SingleDatepicker
            name="date-start"
            date={startDate}
      
            onDateChange={setStartDate}
          />
    
        </VStack>
        <VStack alignItems="flex-start">
          {/* <InputLabel>react-datepicker</InputLabel>
          <DatePicker2 selectedDate={endDate} onChange={setEndDate} /> */}
           <InputLabel>วันที่สิ้นสุด</InputLabel>
          <SingleDatepicker
            name="date-end"
            date={endDate}
          
            onDateChange={setEndDate}
          />
        </VStack>
        <VStack alignItems="flex-start"  >
        <InputLabel>&nbsp;</InputLabel>
        <Button colorScheme='blue' onClick={fetchData}>แสดงรายการ</Button>
        </VStack>
      </HStack>
      </Flex>
      </Flex>
  
    {/* </Card> */}
    <SimpleGrid
            columns={{ base: 1, md: 2, lg: 3, '2xl': 6 }}
            gap='20px'
            mb='20px'
    
          >
            <MiniStatistics
              endContent={
                <IconBox
                  w='56px'
                  h='56px'
                  bg={boxBg}
                  icon={
                    <Icon
                      w='32px'
                      h='32px'
                      as={MdBarChart}
                      color={brandColor}
                    />
                  }
                />
              }
              name={'สมัครทั้งหมด'}
              value={data.data[0]?.counter}
            />
            <MiniStatistics
              endContent={
                <IconBox
                  w='56px'
                  h='56px'
                  bg={boxBg}
                  icon={
                    <Icon
                      w='32px'
                      h='32px'
                      as={MdAttachMoney}
                      color={brandColor}
                    />
                  }
                />
              }
              name='จำนวนสมาชิกที่ฝาก'
              value={data.data[0]?.active.length}
            />
             
            <MiniStatistics
              endContent={
                <IconBox
                  w='56px'
                  h='56px'
                  bg='linear-gradient(90deg, #4481EB 0%, #04BEFE 100%)'
                  icon={<Icon w='28px' h='28px' as={MdAddTask} color='white' />}
                />
              }
              name='ยอดเงินที่ฝาก'
              value={data.data[0]?.total}
            />
            <MiniStatistics
            endContent={
                <IconBox
                  w='56px'
                  h='56px'
                  bg='linear-gradient(90deg, #4481EB 0%, #04BEFE 100%)'
                  icon={<Icon w='28px' h='28px' as={MdAddTask} color='white' />}
                />
              }
              name='ค่าเฉลี่ยยอดที่ฝาก'
              value={isNaN(data.data[0]?.total/data.data[0]?.active.length)?0:(data.data[0]?.total/data.data[0]?.active.length).toLocaleString("en-US",{minimumFractionDigits:2, maximumFractionDigits:2})}
            />
            <MiniStatistics
              endContent={
                <IconBox
                  w='56px'
                  h='56px'
                  bg={boxBg}
                  icon={
                    <Icon
                      w='32px'
                      h='32px'
                      as={MdFileCopy}
                      color={brandColor}
                    />
                  }
                />
              }
              name='จำนวนสมาชิกที่ไม่ได้ฝาก'
              value={data.data[0]?.counter>0?data.data[0]?.counter-data.data[0]?.active.length:0}
            />
          </SimpleGrid>
 
    
   
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
         background='white'
      />
  );
}