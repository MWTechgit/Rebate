# API 

'number_of_bathrooms' => $this->toInt($this->getData('step3-full_bathroom')) + $this->toInt($this->getData('step3-half_bathroom')), //// number_of_bathrooms
Seems pointless, just add them together or use computed property
'number_of_toilets' => trim($this->getData('step3-property_toilet_rebates')), //// number_of_toilets
Seems pointless, just use "application.rebate_count"

Leaving the above out of the API