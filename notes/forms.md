Applicant
Property
    - Address
Owner
    - nullable
    - address
Rebate
Rebate Partner
Application
    - Address
UtilityAccount
    - Address

=====================================

Application
    -> Property
        -> HasOne Address
        -> HasOne ?Owner
    -> Applicant
        -> HasOne ?Reference
           Don't make this editable. Just show it.

Application Detail Page
- Edit Applicant
- Edit Property
- Edit Property Address
- Edit Utility Account
- Edit Utility Account Address

Application Edit Page
- Application
    - Address (if remittance)
    - Rebate
        - No editing of this on form
        - Change rebate count (action)
        - Change rebate (only claims)
- Applicant
- Rebate
- Property
    - Owner
        - Address
    - Address
- Water Utility
    - Address

