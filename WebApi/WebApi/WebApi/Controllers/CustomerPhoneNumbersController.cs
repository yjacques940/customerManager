using Microsoft.AspNetCore.Mvc;
using WebApi.Models;
using WebApi.Services;

namespace WebApi.Controllers
{
    [Route("api/[controller]")]
    [ApiController]
    public class CustomerPhoneNumbersController : BaseController<CustomerPhoneNumberService, CustomerPhoneNumber>
    {
        public CustomerPhoneNumbersController(CustomerPhoneNumberService service) : base(service)
        {
        }
    }
}
