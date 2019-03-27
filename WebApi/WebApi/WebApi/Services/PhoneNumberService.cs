using WebApi.Data;
using WebApi.Models;

namespace WebApi.Services
{
    public class PhoneNumberService : BaseCrudService<PhoneNumber>
    {
        public PhoneNumberService(IWebApiContext context) : base(context)
        {
        }
    }
}
