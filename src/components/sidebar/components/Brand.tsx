// Chakra imports
import { Flex, useColorModeValue,Image } from '@chakra-ui/react';

// Custom components
import { HorizonLogo, PKDLogo, SlackLogo } from 'components/icons/Icons';
import { HSeparator } from 'components/separator/Separator';

export function SidebarBrand() {
	//   Chakra color mode
	let logoColor = useColorModeValue('navy.700', 'white');

	return (
		<Flex alignItems='center' flexDirection='column'  >
			{/* <PKDLogo h='26px' w='175px' my='32px' color={logoColor} /> */}
			<Image
				boxSize='100px'
				objectFit='cover'
				src= '/img/layout/human.jfif'
				alt='Dan Abramov'
				mb='20px'
			/>
			<HSeparator mb='20px' />
		</Flex>
	);
}

export default SidebarBrand;
