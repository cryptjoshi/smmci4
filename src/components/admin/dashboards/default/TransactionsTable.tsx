/* eslint-disable */
'use client'
import {
  Badge,
  Box,
  Flex,
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
  Stack,
  VStack,
  HStack
   
} from '@chakra-ui/react';

import Swal from 'sweetalert2'
 
import withReactContent from 'sweetalert2-react-content';
 
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

import { MdChevronLeft,MdChevronRight } from 'react-icons/md';
import { SearchBar } from 'components/navbar/searchBar/SearchBar';

import Card from 'components/card/Card';
import * as React from 'react';
import { useRouter } from 'next/navigation';
import { useState } from 'react';
import { convertDate } from 'utils';

// Assets
import { InputLabel } from 'components/InputLabel';
import { SingleDatepicker } from 'components/Datepicker/DayzedDatepicker';
import { getTransaction } from 'app/actions/auth';


type RowObj = {
  uid: string;
  bankname: string;
  accountno:string;
  createdAt: string;
  statement_type:string;
  transactionamount:number;
  status: string;
};

const columnHelper = createColumnHelper<RowObj>();
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
export default function TransactionTable(props: { tableData: any }) {
  
  const { tableData } = props;
  const [sorting, setSorting] = useState<SortingState>([]);
  const textColor = useColorModeValue('secondaryGray.900', 'white');
  const textColorSecondary = useColorModeValue('secondaryGray.600', 'white');
  const borderColor = useColorModeValue('gray.200', 'whiteAlpha.100');
  const boxBg = useColorModeValue('secondaryGray.300', 'whiteAlpha.100');

  const brandColor = useColorModeValue('brand.500', 'brand.400');
  const router = useRouter()
  const [transfers,setTransfers] = useState(tableData)
  const [loading, setLoading] = useState(true);
  const [data, setData] =  useState(tableData);
  const [columnFilters, setColumnFilters] = useState<ColumnFiltersState>([]);
  const [globalFilter, setGlobalFilter] =  useState('');
  const [{ pageIndex, pageSize }, setPagination] =useState<PaginationState>({pageIndex: 0,pageSize: 6});
  const [endDate, setEndDate] = useState(new Date());
  const [startDate,setStartDate] = useState(new Date());

const pagination = React.useMemo(() => ({pageIndex,pageSize}),[pageIndex, pageSize]);
 
const createPages = (count: number) => {
  
  let arrPageCount = [];

  for (let i = 1; i <= count; i++) {
      arrPageCount.push(i);
  }

  return arrPageCount;
};

const fetchData =  () =>{
    //console.log('transaction:'+compareDate(startDate,endDate))
    if(compareDate(startDate,endDate))
    {
    getTransaction(startDate.toString(),endDate.toString()).then((result)=>{
      
      setTransfers(result.data)
      setData(result.data)
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
    //  .then((result:any)=>{
     
    //   settableData(result.data)
    //   table.data = result.data
    // })
   }

const columns = [
    columnHelper.accessor('uid', {
      id: 'uid',
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
       
        <Text color='blue.500' fontSize="sm" fontWeight="600"  >
        {info.getValue()}
        </Text>
       
     
  
      ),
    }),
    columnHelper.accessor('accountno', {
      id: 'accountno',
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
    columnHelper.accessor('statement_type', {
      id: 'statement_type',
      header: () => (
        <Text
          justifyContent="space-between"
          align="center"
          fontSize={{ sm: '10px', lg: '12px' }}
          color="gray.400"
        >
         {"ประเภท"}
        </Text>
      ),
      cell: (info:any) => (
        <Badge
        colorScheme={
            info.getValue() === 'Deposit' ? 'green' : 'red'
        }
        color={
            info.getValue() === 'Deposit'
                ? 'green.500'
                : 'red.500'
        }
        fontSize="md"
        fontWeight="500"
    >
       {info.getValue()=="Deposit"?"ฝาก":"ถอน"}
    </Badge>
        // <Text color={info.getValue() =="Deposit" ? 'green.500' : 'red.500'} fontSize="sm" fontWeight="600">
        //   {info.getValue()=="Deposit"?"ฝาก":"ถอน"}
        // </Text>
      ),
    }),
    columnHelper.accessor('transactionamount', {
      id: 'trnasactionamount',
      header: () => (
        <Text
          justifyContent="space-between"
          align="center"
          fontSize={{ sm: '10px', lg: '12px' }}
          color="gray.400"
        >
         {"ยอดเงิน"}
        </Text>
      ),
      cell: (info:any) => (
        <Text color={info.getValue() >0 ? 'green.500' : 'red.500'} fontSize="sm" fontWeight="600" align="right">
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
        
        <> 
        <Badge
        colorScheme={
            info.getValue() === 'verified' ? 'green' : 'red'
        }
        color={
            info.getValue() === 'verified'
                ? 'green.500'
                : 'red.500'
        }
        fontSize="md"
        fontWeight="500"
    >
     {info.getValue()=="verified"?"สำเร็จ":"รอตรวจสอบ"}
    </Badge>
   { info.row.original.statement_type != 'Deposit'?
   <Link href={`/admin/transactions/transfer/${info.row.original.uid}`}>
     <Button
        fontSize="sm"
        size="sm"
        variant="outline"
        fontWeight="200"
        w="15%"
        ml="24px"
        // colorScheme={
        //   'blue' 
        // }
        color={
          'blue'
        }
        //onClick={showdetail(info.row.original.uid)}
        // onClick={router.push(`/admin/transactions/transfer/${info.row.original.uid}`)}
    >
     {info.getValue()=="verified"?"ออโต้":"โอนเงิน"}
    </Button> </Link>: <></>
    
    }
     </>
      ),
    }),
 
  ];
  
const table = useReactTable({
    data,
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


  return (
    <>
    <Card
      flexDirection="column"
      w="100%"
      px="0px"
      overflowX={{ sm: 'scroll', lg: 'hidden' }}
    >
      {/* <Flex align="center" justify="space-between" w="100%" px="10px" mb="20px">
        <Text
          color={textColor}
          fontSize="lg"
          fontWeight="700"
          lineHeight="100%"
          ml="5"
        >
          {"รายการเคลื่อนไหว"}
        </Text>

      </Flex> */}
      <Box minH={`calc(45vh)`}>
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
        <Table variant="simple" color="gray.500" mt="12px" >
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
              .rows.slice(0, 11)
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
                    {pageSize * (pageIndex + 1) <= data.length
                        ? pageSize * (pageIndex + 1)
                        : data.length}{' '}
                    of {data.length} entries
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
                                pageSize === 5
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
                                pageSize === 5
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