// Chakra imports
import { Box, Flex, Avatar, Text, useColorModeValue } from '@chakra-ui/react';
import Card from 'components/card/Card';

export default function Banner(props: {
  banner: string;
  avatar: string | any;
  name: string;
  role: string;
  betamount: number | string;
  turnover: number | string;
  balance: number | string;
  [x: string]: any;
}) {
  const { banner, avatar, name, role, betamount, balance, turnover,payout, ...rest } =
    props;
  // Chakra Color Mode
  const textColorPrimary = useColorModeValue('secondaryGray.900', 'white');
  const textColorSecondary = 'gray.400';
  const borderColor = useColorModeValue(
    'white !important',
    '#111C44 !important',
  );
  return (
    <Card mb={{ base: '0px', lg: '20px' }} alignItems="center" {...rest}>
      <Box
        bg={`url(${banner})`}
        bgSize="cover"
        borderRadius="16px"
        h="131px"
        w="100%"
      />
      <Avatar
        mx="auto"
        src={avatar.src}
        h="87px"
        w="87px"
        mt="-43px"
        border="4px solid"
        borderColor={borderColor}
      />
      <Text color={textColorPrimary} fontWeight="bold" fontSize="xl" mt="10px">
        {name}
      </Text>
      <Text color={textColorSecondary} fontSize="sm">
        {role}
      </Text>
      { betamount!=""?<></>:
      <Flex w="max-content" mx="auto" mt="26px">
        <Flex mx="auto" me="60px" alignItems="center" flexDirection="column">
          <Text color={textColorPrimary} fontSize="2xl" fontWeight="700">
            {betamount}
          </Text>
          <Text color={textColorSecondary} fontSize="sm" fontWeight="400">
            ลงทุน
          </Text>
        </Flex>
        <Flex mx="auto" me="60px" alignItems="center" flexDirection="column">
          <Text color={textColorPrimary} fontSize="2xl" fontWeight="700">
            {payout}
          </Text>
          <Text color={textColorSecondary} fontSize="sm" fontWeight="400">
            ได้/เสีย
          </Text>
        </Flex>
        <Flex mx="auto" alignItems="center" flexDirection="column">
          <Text color={textColorPrimary} fontSize="2xl" fontWeight="700">
            {balance}
          </Text>
          <Text color={textColorSecondary} fontSize="sm" fontWeight="400">
            คงเหลือ
          </Text>
        </Flex>
      </Flex>
      }
    </Card>
  );
}
