using Microsoft.AspNetCore.Mvc;
using System.Collections.Generic;
using System.Linq;
using WebApi.Data;
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

        public List<PhoneNumber> GetPhoneNumbersFromCustomerList(int customerId)
        {
            return Context.PhoneNumbers.Where(c => c.IdCustomer == customerId).ToList();
        }
    }
}
