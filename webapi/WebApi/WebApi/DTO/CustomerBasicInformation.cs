using System.Collections.Generic;

namespace WebApi.DTO
{
    public class CustomerBasicInformation
    {
        public int Id;
        public string Email { get; set; }
        public string FullName { get; set; }
        public List<PhoneNumberAndTypesInformation> phoneNumbers { get; set; }
    }
}