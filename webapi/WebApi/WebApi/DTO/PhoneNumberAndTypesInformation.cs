using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;

namespace WebApi.DTO
{
    public class PhoneNumberAndTypesInformation
    {
        public string PhoneType { get; set; }
        public string Phone { get; set; }
        public string Extension { get; set; }
        public int IdPhoneType { get; set; }
        public int IdCustomer { get; set; }
    }
}
