using Microsoft.AspNetCore.Mvc;
using System.Collections.Generic;
using System.Linq;
using WebApi.Data;
using WebApi.Models;

namespace WebApi.Services
{
    public class CustomerPhoneNumberService : BaseCrudService<CustomerPhoneNumber>
    {
        public CustomerPhoneNumberService(IWebApiContext context) : base(context)
        {
        }

        public ActionResult<IEnumerable<PhoneNumber>> GetPhoneNumbersFromCustomer(int customerId)
        {
            return GetPhoneNumbersFromCustomer(customerId);
        }

        public List<PhoneNumber> GetPhoneNumbersFromCustomerList(int customerId)
        {
            List<int> phoneNumberIDs = Context.CustomerPhoneNumbers
                .Where(c => c.IdCustomer == customerId).Select(c => c.IdPhoneNumber).ToList();
            return Context.PhoneNumbers.Where(c => phoneNumberIDs.Contains(c.Id)).ToList();
        }
    }
}
