using System.Collections.Generic;
using Microsoft.AspNetCore.Mvc;
using WebApi.Models;
using WebApi.Services;

namespace WebApi.Controllers
{
    [Route("api/[controller]")]
    [ApiController]
    public class CustomerPhoneNumbersController : BaseReaderController<CustomerPhoneNumberService, CustomerPhoneNumber>
    {
        public CustomerPhoneNumbersController(CustomerPhoneNumberService service) : base(service)
        {
        }

        [HttpGet, Route("ForCustomer/{id}")]
        public List<PhoneNumber> GetPhoneNumberByCustomer(int id){
            return Service.GetPhoneNumbersFromCustomerList(id);
        }
    }
}
