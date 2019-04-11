namespace WebApi.Models
{
    public class State : BaseModel
    {
        public string Name { get; set; }
        public string Code { get; set; }
        public int IdCountry { get; set; }
    }
}
