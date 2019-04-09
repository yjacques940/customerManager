using Microsoft.AspNetCore.Mvc;
using System.Collections.Generic;
using WebApi.Models;
using WebApi.Services;

namespace WebApi.Controllers
{
    public class PhoneNumbersController : BaseController<PhoneNumberService, PhoneNumber>
    {
        public PhoneNumbersController(PhoneNumberService service) : base(service)
        {
        }

        [HttpGet, Route("ForCustomer/{id}")]
        public List<PhoneNumber> GetPhoneNumberByCustomer(int id)
        {
            return Service.GetPhoneNumbersFromCustomerList(id);
        }
    }
}
