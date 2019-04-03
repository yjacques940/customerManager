using WebApi.Models;
using WebApi.Services;

namespace WebApi.Controllers
{
    public class PhoneNumbersController : BaseController<PhoneNumberService, PhoneNumber>
    {
        public PhoneNumbersController(PhoneNumberService service) : base(service)
        {
        }
    }
}
