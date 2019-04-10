using Microsoft.AspNetCore.Mvc;
using System.Collections.Generic;
using System.Linq;
using WebApi.Data;
using WebApi.DTO;
using WebApi.Models;

namespace WebApi.Services
{
    public class PhoneNumberService : BaseCrudService<PhoneNumber>
    {
        public PhoneNumberService(IWebApiContext context) : base(context)
        {
        }

        public ActionResult<IEnumerable<PhoneNumber>> GetPhoneNumbersFromCustomer(int customerId)
        {
            return GetPhoneNumbersFromCustomer(customerId);
        }

        public List<PhoneNumberAndTypesInformation> GetPhoneNumbersFromCustomerList(int customerId)
        {
            var query = (from phoneNumber in Context.PhoneNumbers
                join phoneType in Context.PhoneTypes on phoneNumber.IdPhoneType equals phoneType.Id
                where phoneNumber.IdCustomer == customerId && phoneNumber.IsActive && phoneType.IsActive
                select new PhoneNumberAndTypesInformation()
                {
                    Phone = phoneNumber.Phone,
                    PhoneType = phoneType.Name,
                    IdPhoneType = phoneType.Id,
                    Extension = phoneNumber.Extension,
                    IdCustomer = customerId
                }).ToList();
            return query;
        }
    }
}
