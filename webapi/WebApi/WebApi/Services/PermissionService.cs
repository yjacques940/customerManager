using WebApi.Data;
using WebApi.Models;

namespace WebApi.Services
{
    public class PermissionService : BaseCrudService<Permission>
    {
        public PermissionService(IWebApiContext context) : base(context)
        {
        }
    }
}
